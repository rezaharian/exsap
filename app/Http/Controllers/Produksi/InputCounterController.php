<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\logCap;
use App\Models\logExtr;
use App\Models\pegawai;
use App\Models\superker;
use App\Models\vmacunit;
use App\Models\wa_lpku_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InputCounterController extends Controller
{
    // 
    public function index(Request $request)
    {

        $data = wa_lpku_detail::limit(20)->get();

        $line = vmacunit::select('LINE')->where('STATUS', 'AKTIF')->orderBy('NL', 'ASC')->GET();
        // DD($line);

        return view('produksi.inputcounter.index', compact('line', 'data'));
    }


    public function list(Request $request)
    {
        $jenis = $request->jenis;
        $line = $request->line;

        // Validate inputs
        $request->validate([
            'jenis' => 'required|string|in:Extruder,Printing',
            'line' => 'required|string',
        ]);
        // dd($request->toArray());
        // Fetch the data based on jenis
        if ($jenis == 'Extruder') {
            $datalog = logExtr::select(
                DB::raw('MAX(id) as max_id'),
                'tanggal',
                'spk_nomor',
                'line',
                'shift',
                'no_reg',
                DB::raw('SUM(hasil) as total_hasil')
            )
                ->where('line', $line)
                ->groupBy('tanggal', 'spk_nomor', 'line', 'shift', 'no_reg')
                ->orderBy('max_id', 'desc')
                ->get();
        } elseif ($jenis == 'Printing') {
            $datalog = logCap::select(
                DB::raw('MAX(id) as max_id'),
                'tanggal',
                'spk_nomor',
                'line',
                'shift',
                'no_reg',
                DB::raw('SUM(hasil) as total_hasil')
            )
                ->where('line', $line)
                ->groupBy('tanggal', 'spk_nomor', 'line', 'shift', 'no_reg')
                ->orderBy('max_id', 'desc')
                ->get();
        } else {
            // Handle invalid jenis if needed
            $datalog = collect(); // Empty collection if no matching jenis
        }

        $spk = superker::select('SPK_NOMOR', 'LINE')
            ->orderBy('SPK_TGL', 'DESC')
            ->limit(800)
            ->get();

        $noreg = pegawai::orderBy('nama_asli', 'ASC')
            ->where('jns_peg', '!=', 'SATPAM')
            ->where('jns_peg', '!=', 'KEBERSIHAN')
            ->whereNull('tgl_keluar')
            ->get();

        // Debugging purpose, remove or comment after testing
        // dd($noreg->toArray());

        // Return the view with the fetched data
        return view('produksi.inputcounter.list', compact('datalog', 'jenis', 'line', 'spk', 'noreg'));
    }

    public function create(Request $request)
    {
        // Tangkap data dari query string
        $tanggal = $request->input('tanggal');
        $spk_nomor = $request->input('spk_nomor');
        $line = $request->input('line');
        $shift = $request->input('shift');
        $no_reg = $request->input('no_reg');
        $jenis = $request->input('jenis');
        $leader = $request->input('leader');
        $spv = $request->input('spv');
        // dd($request->toarray());
        $spk = superker::where('SPK_NOMOR', $spk_nomor)->select('SPK_NOMOR', 'LINE')->first();

        if (!$spk) {
            // Jika tidak ada, lempar error atau kembalikan pesan error
            return redirect()->back()->withErrors(['spk_nomor' => 'Nomor SPK tidak ditemukan, mohon pilih SPK yang benar']);
        }
        if ($jenis == 'Extruder') {

            // Ambil data dari database
            $data = logExtr::where('tanggal', $tanggal)
                ->where('spk_nomor', $spk_nomor)
                ->where('line', $line)
                ->where('shift', $shift)
                ->get();
        } elseif ($jenis == 'Printing') {
            // Ambil data dari database
            $data = logCap::where('tanggal', $tanggal)
                ->where('spk_nomor', $spk_nomor)
                ->where('line', $line)
                ->where('shift', $shift)
                ->get();
        }


        // Memeriksa apakah data ditemukan
        if ($data->isNotEmpty()) {
            return view('produksi.inputcounter.edit', compact('data', 'jenis'))
                ->with('success', 'Data sudah ada, silahkan edit');
        }

        // Kirim data ke view create jika data tidak ditemukan
        return view('produksi.inputcounter.create', compact('tanggal', 'spk_nomor', 'line', 'shift', 'jenis', 'no_reg', 'leader', 'spv'));
    }


    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'jenis' => 'required|string|in:Extruder,Printing',
            'tanggal' => 'required|date', // Tambahkan 'required' jika tanggal harus ada
            'spk_nomor' => 'string|nullable',
            'line' => 'string|nullable',
            'shift' => 'string|nullable',
            'no_reg' => 'string|nullable',
            'data' => 'required|array',
            'data.*.idf' => 'string|nullable',
            'data.*.jam' => 'string|nullable',
            'data.*.reset_count' => 'string|nullable',
            'data.*.akhir' => 'integer|nullable',
            'data.*.hasil' => 'integer|nullable',
            'data.*.reset_time' => 'string|nullable',
            'data.*.jam_akhir' => 'string|nullable',
            'data.*.keterangan' => 'string|nullable',
            'data.*.waktu' => 'string|nullable',
            'data.*.setting' => 'nullable', // 
            'data.*.jumlah_waste_setting' => 'nullable|numeric',
            'leader' => 'string|nullable',
            'spv' => 'string|nullable',

        ]);


        if ($validatedData['jenis'] === 'Extruder') {
            foreach ($validatedData['data'] as $dataItem) {
                $setting = !empty($dataItem['setting']) ? 'ya' : null;
                $jumlah_waste_setting = $setting === 'ya' ? $dataItem['jumlah_waste_setting'] ?? null : null;

                logExtr::create([
                    'tanggal' => $validatedData['tanggal'],
                    'spk_nomor' => $validatedData['spk_nomor'],
                    'line' => $validatedData['line'],
                    'shift' => $validatedData['shift'],
                    'no_reg' => $validatedData['no_reg'],
                    // 'idf' => $dataItem['idf'],
                    'ordinal' => null,
                    'jam' => $dataItem['jam'],
                    'reset_count' => $dataItem['reset_count'],
                    'akhir' => $dataItem['akhir'],
                    'hasil' => $dataItem['hasil'],
                    'reset_time' => $dataItem['reset_time'],
                    'jam_akhir' => $dataItem['jam_akhir'],
                    'keterangan' => $dataItem['keterangan'],
                    'waktu' => $dataItem['waktu'],
                    'setting' => $setting,
                    'jumlah_waste_setting' => $jumlah_waste_setting,
                    'leader' => $validatedData['leader'],
                    'spv' => $validatedData['spv'],


                ]);
            }
        } elseif ($validatedData['jenis'] === 'Printing') {
            foreach ($validatedData['data'] as $dataItem) {
                $setting = !empty($dataItem['setting']) ? 'ya' : null;
                $jumlah_waste_setting = $setting === 'ya' ? $dataItem['jumlah_waste_setting'] ?? null : null;

                logCap::create([
                    'tanggal' => $validatedData['tanggal'],
                    'spk_nomor' => $validatedData['spk_nomor'],
                    'line' => $validatedData['line'],
                    'shift' => $validatedData['shift'],
                    'no_reg' => $validatedData['no_reg'],
                    // 'idf' => $dataItem['idf'],
                    'ordinal' => null,
                    'jam' => $dataItem['jam'],
                    'reset_count' => $dataItem['reset_count'],
                    'akhir' => $dataItem['akhir'],
                    'hasil' => $dataItem['hasil'],
                    'reset_time' => $dataItem['reset_time'],
                    'jam_akhir' => $dataItem['jam_akhir'],
                    'keterangan' => $dataItem['keterangan'],
                    'waktu' => $dataItem['waktu'],
                    'setting' => $setting,
                    'jumlah_waste_setting' => $jumlah_waste_setting,
                    'leader' => $validatedData['leader'],
                    'spv' => $validatedData['spv'],
                ]);
            }
        }
        // Simpan data ke dalam database


        // Kembalikan respons
        return back()->with('success', 'data sudah tersimpan');
    }


    public function edit($tanggal, $spk_nomor, $line, $shift, $no_reg, $jenis)
    {
        // Kembali dari underscore ke slash
        $spk_nomor = str_replace('_', '/', $spk_nomor);

        // Mencari data berdasarkan parameter
        if ($jenis == 'Extruder') {
            $data = logExtr::where('tanggal', $tanggal)
                ->where('spk_nomor', $spk_nomor)
                ->where('line', $line)
                ->where('shift', $shift)
                ->where('no_reg', $no_reg)
                ->get();
        } elseif ($jenis == 'Printing') {
            $data = logCap::where('tanggal', $tanggal)
                ->where('spk_nomor', $spk_nomor)
                ->where('line', $line)
                ->where('shift', $shift)
                ->where('no_reg', $no_reg)
                ->get();
        }
        // Memastikan data ditemukan
        if (!$data) {
            return redirect()->route('produksi.inputcounter.index')->with('error', 'Data not found.');
        }

        // dd($data->toArray());
        // Mengembalikan tampilan edit dengan data yang diambil
        return view('produksi.inputcounter.edit', compact('data', 'jenis'));
    }

    public function update(Request $request, $tanggal, $spk_nomor, $line, $shift, $no_reg)
    {
        // Validasi input
        $validatedData = $request->validate([
            'jenis' => 'required|string|in:Extruder,Printing', // Tambahkan validasi untuk jenis
            'tanggal' => 'required|date',
            'spk_nomor' => 'string|nullable',
            'line' => 'string|nullable',
            'shift' => 'string|nullable',
            'no_reg' => 'string|nullable',
            'data' => 'required|array',
            'data.*.id' => 'integer|nullable',  // pastikan 'id' sudah ada di data yang diupdate
            'data.*.jam' => 'string|nullable',
            'data.*.reset_count' => 'string|nullable',
            'data.*.akhir' => 'integer|nullable',
            'data.*.hasil' => 'integer|nullable',
            'data.*.reset_time' => 'string|nullable',
            'data.*.jam_akhir' => 'string|nullable',
            'data.*.stat_spk' => 'string|nullable',
            'data.*.keterangan' => 'string|nullable',
            'data.*.waktu' => 'string|nullable',
            'data.*.setting' => 'nullable',
            'data.*.jumlah_waste_setting' => 'nullable|numeric',
            'leader' => 'string|nullable',
            'spv' => 'string|nullable',
        ]);


        // dd($request->toArray());
        // Iterasi melalui data untuk memperbarui atau membuat data baru
        foreach ($validatedData['data'] as $dataItem) {
            $setting = !empty($dataItem['setting']) ? 'ya' : null;
            $jumlah_waste_setting = $setting === 'ya' ? $dataItem['jumlah_waste_setting'] ?? null : null;

            if (isset($dataItem['id'])) {
                // Jika 'id' ada, cari record yang sudah ada untuk diupdate
                if ($validatedData['jenis'] === 'Extruder') {
                    $logCap = logExtr::find($dataItem['id']);
                    if ($logCap) {
                        // Update record yang ditemukan
                        $logCap->update(([
                            'tanggal' => $validatedData['tanggal'],
                            'spk_nomor' => $validatedData['spk_nomor'],
                            'line' => $validatedData['line'],
                            'shift' => $validatedData['shift'],
                            'no_reg' => $validatedData['no_reg'],
                            'jam' => $dataItem['jam'],
                            'reset_count' => $dataItem['reset_count'],
                            'akhir' => $dataItem['akhir'],
                            'hasil' => $dataItem['hasil'],
                            'reset_time' => $dataItem['reset_time'],
                            'jam_akhir' => $dataItem['jam_akhir'],
                            'stat_spk' => $dataItem['stat_spk'],
                            'keterangan' => $dataItem['keterangan'],
                            'waktu' => $dataItem['waktu'],
                            'setting' => $setting,
                            'jumlah_waste_setting' => $jumlah_waste_setting,
                            'leader' => $validatedData['leader'],
                            'spv' => $validatedData['spv'],
                        ]));
                    } else {
                        return back()->withErrors(['data' => "Record with ID {$dataItem['id']} not found."]);
                    }
                } elseif ($validatedData['jenis'] === 'Printing') {
                    // Lakukan hal yang sama untuk logCap jika diperlukan
                    $logCap = logCap::find($dataItem['id']);
                    if ($logCap) {
                        $logCap->update(([
                            'tanggal' => $validatedData['tanggal'],
                            'spk_nomor' => $validatedData['spk_nomor'],
                            'line' => $validatedData['line'],
                            'shift' => $validatedData['shift'],
                            'no_reg' => $validatedData['no_reg'],
                            'jam' => $dataItem['jam'],
                            'reset_count' => $dataItem['reset_count'],
                            'akhir' => $dataItem['akhir'],
                            'hasil' => $dataItem['hasil'],
                            'reset_time' => $dataItem['reset_time'],
                            'jam_akhir' => $dataItem['jam_akhir'],
                            'stat_spk' => $dataItem['stat_spk'],
                            'keterangan' => $dataItem['keterangan'],
                            'waktu' => $dataItem['waktu'],
                            'setting' => $setting,

                            'jumlah_waste_setting' => $jumlah_waste_setting,
                            'leader' => $validatedData['leader'],
                            'spv' => $validatedData['spv'],
                        ]));
                    } else {
                        return back()->withErrors(['data' => "Record with ID {$dataItem['id']} not found."]);
                    }
                }
            } else {
                // Jika tidak ada 'id', buat record baru
                if ($validatedData['jenis'] === 'Extruder') {
                    logCap::create(array_filter([
                        'tanggal' => $validatedData['tanggal'],
                        'spk_nomor' => $validatedData['spk_nomor'],
                        'line' => $validatedData['line'],
                        'shift' => $validatedData['shift'],
                        'no_reg' => $validatedData['no_reg'],
                        'ordinal' => null,  // bisa diganti sesuai kebutuhan
                        'jam' => $dataItem['jam'],
                        'reset_count' => $dataItem['reset_count'],
                        'akhir' => $dataItem['akhir'],
                        'hasil' => $dataItem['hasil'],
                        'reset_time' => $dataItem['reset_time'],
                        'jam_akhir' => $dataItem['jam_akhir'],
                        'stat_spk' => $dataItem['stat_spk'],
                        'keterangan' => $dataItem['keterangan'],
                        'waktu' => $dataItem['waktu'],
                        'setting' => $setting,

                        'jumlah_waste_setting' => $jumlah_waste_setting,
                        'leader' => $validatedData['leader'],
                        'spv' => $validatedData['spv'],
                    ]));
                } elseif ($validatedData['jenis'] === 'Printing') {
                    // Buat record baru untuk Printing jika tidak ada 'id'
                    logCap::create(array_filter([
                        'tanggal' => $validatedData['tanggal'],
                        'spk_nomor' => $validatedData['spk_nomor'],
                        'line' => $validatedData['line'],
                        'shift' => $validatedData['shift'],
                        'no_reg' => $validatedData['no_reg'],
                        'ordinal' => null,
                        'jam' => $dataItem['jam'],
                        'reset_count' => $dataItem['reset_count'],
                        'akhir' => $dataItem['akhir'],
                        'hasil' => $dataItem['hasil'],
                        'reset_time' => $dataItem['reset_time'],
                        'jam_akhir' => $dataItem['jam_akhir'],
                        'stat_spk' => $dataItem['statspk'],
                        'keterangan' => $dataItem['keterangan'],
                        'waktu' => $dataItem['waktu'],
                        'setting' => $setting,

                        'jumlah_waste_setting' => $jumlah_waste_setting,
                        'leader' => $validatedData['leader'],
                        'spv' => $validatedData['spv'],
                    ]));
                }
            }
        }

        // Kembalikan respons
        return back()->with('success', 'Data berhasil diperbarui');
    }

    public function delete(Request $request, $tanggal, $spk_nomor, $line, $shift, $no_reg, $jenis)
    {
        // Mencari data berdasarkan parameter

        if ($jenis == 'Extruder') {
            $deletedCount = logExtr::where('tanggal', $tanggal)
                ->where('spk_nomor', str_replace('_', '/', $spk_nomor))
                ->where('line', $line)
                ->where('shift', $shift)
                ->where('no_reg', $no_reg)
                ->delete(); // Menghapus semua data yang cocok
        } elseif ($jenis == 'Printing') {
            $deletedCount = logCap::where('tanggal', $tanggal)
                ->where('spk_nomor', str_replace('_', '/', $spk_nomor))
                ->where('line', $line)
                ->where('shift', $shift)
                ->where('no_reg', $no_reg)
                ->delete(); // Menghapus semua data yang cocok
        }


        // Cek apakah ada data yang dihapus
        if ($deletedCount > 0) {
            return redirect()->route('produksi.inputcounter.index') // Ganti dengan route yang sesuai
                ->with('success', 'Data berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Tidak ada data yang ditemukan untuk dihapus.');
        }
    }
}
