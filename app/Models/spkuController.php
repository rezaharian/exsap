<?php

namespace App\Http\Controllers;

use App\Models\group_spku;
use App\Models\pegawai;
use App\Models\spku_d;
use App\Models\spku_h;
use App\Models\superker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class spkuController extends Controller
{
    //

    public function index()
    {
        $spku = spku_h::orderBy('spku_cod', 'desc')->get();

        return view('admin.input.spku.index', compact('spku'));
    }
    public function create()
    {

        $jnpn = group_spku::all();
        // dd($jnpn);


        $prefix = Carbon::now()->format('ym');

        // Ambil data terakhir dari tabel
        $last = spku_h::select('spku_cod')
            ->orderByDesc('spku_cod')
            ->first();

        if ($last) {
            $lastCode = $last->spku_cod;
            $lastYearMonth = substr($lastCode, 0, 4); // ambil yyMM
            $lastNumber = (int) substr($lastCode, -4); // ambil 4 digit terakhir

            // Jika masih di tahun yang sama → lanjut urutan
            if ($lastYearMonth === $prefix) {
                $nextNumber = $lastNumber + 1;
            } else {
                // Tahun beda → mulai dari 1 lagi
                $nextNumber = 1;
            }
        } else {
            // Jika belum ada data sama sekali
            $nextNumber = 1;
        }

        // Format nomor 4 digit (misal: 0001, 0002, dst)
        $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // Gabungkan prefix + nomor urut
        $newSpkuCode = $prefix . $formattedNumber;
        return view('admin.input.spku.create', compact('jnpn', 'newSpkuCode'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('query');
        $type  = $request->get('type');

        try {
            if ($type === 'spk') {
                $spk = superker::whereRaw('LOWER(SPK_NOMOR) LIKE ?', ['%' . strtolower($query) . '%'])
                    ->limit(100)
                    ->orderBy('SPK_TGL', 'desc')
                    ->get([
                        'SPK_NOMOR',
                        'PRODUC_NAM as produc_nam',
                        'CUSTOM_NAM as custom_nam',
                        'LINE as line',
                        'SPK_TGL as tgl_input',
                        'ALU_SIZE as produc_uk',
                    ]);
                return response()->json($spk);
            }

            if ($type === 'pegawai') {
                $pegawai = pegawai::whereNotIn('bagian', ['security', 'kebersihan', 'direksi', 'KEBERSIHAN', 'SATPAM'])
                    ->whereNotIn('jns_peg', ['security', 'kebersihan'])
                    ->whereNull('tgl_keluar')
                    ->whereRaw('LOWER(nama_asli) LIKE ?', ['%' . strtolower($query) . '%'])
                    ->orderBy('no_payroll', 'asc')
                    ->limit(100)
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id' => $item->nama_asli,
                            'text' => $item->nama_asli
                        ];
                    });
                return response()->json($pegawai);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }




    public function store(Request $request)
    {
        // 🔹 Validasi data header & detail
        $validatedData = $request->validate([
            // ====== HEADER ======
            'spku_cod' => 'required|unique:spku_h,spku_cod',
            'spk_nomor' => 'required|string|max:50',
            'produc_nam' => 'required|string|max:100',
            'custom_nam' => 'required|string|max:100',
            'produc_uk' => 'required|string|max:50',
            'line' => 'required|string|max:50',
            'jn_lpku' => 'required|string|max:50',
            'unit' => 'required|string|max:20',
            'shift' => 'required|string|max:10',
            'tgl_input' => 'required|date',
            'keterangan' => 'nullable|string|max:100',
            'dilaporkan' => 'required|string|max:50',

            'jam' => 'required|date_format:H:i',
            'operator' => 'required|max:50',

            // ====== DETAIL ======
            'int' => 'required|array|min:1',
            'int.*' => 'required|min:1',

            'kd_grup' => 'required|array|min:1',
            'kd_grup.*' => 'required|min:1',

            'jn_penyimpangan' => 'required|array|size:' . count($request->int),
            'jn_penyimpangan.*' => 'required|string|max:255',

            'penyebab' => 'nullable|array',
            'penyebab.*' => 'nullable|string|max:255',

            'perbaikan' => 'nullable|array',
            'perbaikan.*' => 'nullable|string|max:255',

            'tgl_perbaikan' => 'nullable|array',
            'tgl_perbaikan.*' => 'nullable|date',

            'pencegahan' => 'nullable|array',
            'pencegahan.*' => 'nullable|string|max:255',

            'tgl_pre' => 'nullable|array',
            'tgl_pre.*' => 'nullable|date',


        ]);

        // 🔹 Simpan Header
        $spku = spku_h::create([
            'spku_cod'   => $validatedData['spku_cod'],
            'spk_nomor'  => $validatedData['spk_nomor'],
            'produc_nam' => $validatedData['produc_nam'],
            'custom_nam' => $validatedData['custom_nam'],
            'produc_uk'  => $validatedData['produc_uk'],
            'line'       => $validatedData['line'],
            'jn_lpku'    => $validatedData['jn_lpku'],
            'unit'       => $validatedData['unit'],
            'dilaporkan'       => $validatedData['dilaporkan'],
            'keterangan'       => $validatedData['keterangan'],
            'shift'      => $validatedData['shift'],
            'jam'      => $validatedData['jam'],
            'operator'      => $validatedData['operator'],
            'tgl_input'  => $validatedData['tgl_input'],
        ]);

        // 🔹 Simpan Detail (aman walau beberapa kolom kosong)
        foreach ($validatedData['int'] as $i => $int) {
            spku_d::create([
                'spku_cod'        => $spku->spku_cod,
                'int'             => $int,
                'id_no'             => $int,
                'jn_penyimpangan' => $validatedData['jn_penyimpangan'][$i] ?? null,
                'kd_grup' => $validatedData['kd_grup'][$i] ?? null,
                'penyebab'        => $validatedData['penyebab'][$i] ?? null,
                'perbaikan'       => $validatedData['perbaikan'][$i] ?? null,
                'tgl_perbaikan'   => $validatedData['tgl_perbaikan'][$i] ?? null,
                'pencegahan'      => $validatedData['pencegahan'][$i] ?? null,
                'tgl_pre'         => $validatedData['tgl_pre'][$i] ?? null,
                'tgl_input'         => $spku->tgl_input,

            ]);
        }

        return redirect()->route('ad.spku.index')->with('success', 'Data SPKu berhasil disimpan.');
    }


    public function show($id)
    {
        $spku = spku_h::with('details')->findOrFail($id);
        return view('admin.input.spku.show', compact('spku'));
    }


    public function edit($id)
    {
        $spku = spku_h::with('details')->findOrFail($id);
        $jnpn = group_spku::all();

        return view('admin.input.spku.edit', compact('spku', 'jnpn'));
    }

    // ===== UPDATE =====
    public function update(Request $request, $id)
    {
        $spku = spku_h::findOrFail($id);

        // Validasi header
        $validatedData = $request->validate([
            'spk_nomor' => 'required|string|max:50',
            'produc_nam' => 'required|string|max:100',
            'custom_nam' => 'required|string|max:100',
            'produc_uk' => 'required|string|max:50',
            'line' => 'required|string|max:50',
            'jn_lpku' => 'required|string|max:50',
            'unit' => 'required|string|max:20',
            'shift' => 'required|string|max:10',
            'tgl_input' => 'required|date',
            'jam' => 'required|date_format:H:i',
            'operator' => 'required|max:50',
            'keterangan' => 'nullable|string|max:100',
            'dilaporkan' => 'required|string|max:50',

            // Detail
            'int' => 'required|array|min:1',
            'int.*' => 'required|min:1',
            'kd_grup' => 'required|array|min:1',
            'kd_grup.*' => 'required|min:1',
            'jn_penyimpangan' => 'required|array|size:' . count($request->int),
            'jn_penyimpangan.*' => 'required|string|max:255',
            'penyebab' => 'nullable|array',
            'penyebab.*' => 'nullable|string|max:255',
            'perbaikan' => 'nullable|array',
            'perbaikan.*' => 'nullable|string|max:255',
            'tgl_perbaikan' => 'nullable|array',
            'tgl_perbaikan.*' => 'nullable|date',
            'pencegahan' => 'nullable|array',
            'pencegahan.*' => 'nullable|string|max:255',
            'tgl_pre' => 'nullable|array',
            'tgl_pre.*' => 'nullable|date',

        ]);

        // Update header
        $spku->update([
            'spk_nomor' => $validatedData['spk_nomor'],
            'produc_nam' => $validatedData['produc_nam'],
            'custom_nam' => $validatedData['custom_nam'],
            'produc_uk' => $validatedData['produc_uk'],
            'line' => $validatedData['line'],
            'jn_lpku' => $validatedData['jn_lpku'],
            'unit' => $validatedData['unit'],
            'shift' => $validatedData['shift'],
            'dilaporkan' => $validatedData['dilaporkan'],
            'keterangan' => $validatedData['keterangan'],
            'jam' => $validatedData['jam'],
            'operator' => $validatedData['operator'],
            'tgl_input' => $validatedData['tgl_input'],
        ]);

        // Hapus semua detail lama
        $spku->details()->delete();

        // Simpan detail baru
        foreach ($validatedData['int'] as $i => $int) {
            spku_d::create([
                'spku_cod' => $spku->spku_cod,
                'int' => $int,
                'id_no' => $int,
                'kd_grup' => $validatedData['kd_grup'][$i] ?? null,
                'jn_penyimpangan' => $validatedData['jn_penyimpangan'][$i] ?? null,
                'penyebab' => $validatedData['penyebab'][$i] ?? null,
                'perbaikan' => $validatedData['perbaikan'][$i] ?? null,
                'tgl_perbaikan' => $validatedData['tgl_perbaikan'][$i] ?? null,
                'pencegahan' => $validatedData['pencegahan'][$i] ?? null,
                'tgl_pre' => $validatedData['tgl_pre'][$i] ?? null,
                'tgl_input' => $spku->tgl_input,

            ]);
        }

        return redirect()->route('ad.spku.index')->with('success', 'Data SPKu berhasil disimpan.');
    }

    // Hapus SPKu keseluruhan
    public function destroy($id)
    {
        $spku = spku_h::findOrFail($id);

        // Hapus semua detail terlebih dahulu (jika ada)
        $spku->details()->delete();

        // Hapus header SPKu
        $spku->delete();

        return redirect()->route('ad.spku.index')
            ->with('success', 'SPKu berhasil dihapus');
    }


    public function rekap(Request $request)
    {

        if ($request->has('tahun')) {
            $tahun = $request->input('tahun');
        } else {
            $tahun = date('Y');
        }

        // dd('test');
        //perline---------------------------------------------------------------
        $totalspkuperline = spku_h::orderBy('spku_cod', 'desc')
            ->groupBy('line')
            ->whereYear('tgl_input', '=', $tahun)
            ->select('line', DB::raw('COUNT(*) as total_spku'))
            ->orderBy('line', 'asc')
            ->get();
        // dd($spku);
        //perjenis--------------------------------------------------------------
        $jenisspku = spku_h::groupBy('jn_lpku')
            ->whereYear('tgl_input', '=', $tahun)
            ->select('jn_lpku', DB::raw('COUNT(*) as total_spku'))
            ->orderBy('jn_lpku', 'asc')
            ->get();

        // Hitung total semua SPKu
        $totalSemua = $jenisspku->sum('total_spku');

        // Tambahkan kolom persentase ke setiap item
        $jenisspku = $jenisspku->map(function ($item) use ($totalSemua) {
            $item->persentase = round(($item->total_spku / $totalSemua) * 100, 2); // 2 desimal
            return $item;
        });



        //perunit---------------------------------------------------------------
        $totalSpku = DB::table('spku_h')
            ->join('spku_d', 'spku_h.spku_cod', '=', 'spku_d.spku_cod')
            ->whereYear('spku_h.tgl_input', $tahun)
            ->count();
        $unitspku = DB::table('spku_h')
            ->join('spku_d', 'spku_h.spku_cod', '=', 'spku_d.spku_cod')
            ->join('group_spku', 'spku_d.kd_grup', '=', 'group_spku.kd_grup')
            ->whereYear('spku_h.tgl_input', $tahun)
            ->select(
                'spku_h.unit',
                DB::raw('COUNT(*) as total_spku')
            )
            ->groupBy('spku_h.unit')
            ->orderBy('spku_h.unit', 'asc')
            ->get();
        $spkuperunit = [];

        foreach ($unitspku as $row) {
            $problems = DB::table('spku_h')
                ->join('spku_d', 'spku_h.spku_cod', '=', 'spku_d.spku_cod')
                ->join('group_spku', 'spku_d.kd_grup', '=', 'group_spku.kd_grup')
                ->whereYear('spku_h.tgl_input', date('Y'))
                ->where('spku_h.unit', $row->unit)
                ->select('group_spku.penyimpangan', DB::raw('COUNT(*) as jumlah'))
                ->groupBy('group_spku.penyimpangan')
                ->orderByDesc('jumlah')
                ->limit(2)
                ->pluck('group_spku.penyimpangan')
                ->toArray();

            $spkuperunit[] = [
                'unit' => $row->unit,
                'total' => $row->total_spku,
                'persen' => round(($row->total_spku / $totalSpku) * 100, 1),
                'problems' => $problems,
            ];
        }


        //perbulan 
        $spkuperbulan = spku_h::select(
            DB::raw('MONTH(tgl_input) as bulan'),
            'line',
            DB::raw('COUNT(*) as total_spku')
        )
            ->whereYear('tgl_input', '=', $tahun)
            ->groupBy(DB::raw('MONTH(tgl_input)'), 'line')
            ->orderBy(DB::raw('MONTH(tgl_input)'))
            ->get();


        // dd($spkuperbulan->toArray());

        return view('admin.input.spku.rekap', compact('totalspkuperline', 'spkuperunit', 'jenisspku', 'tahun', 'spkuperbulan'));
    }
}
