<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\HrProd;
use App\Models\vmacunit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TargetHarianController extends Controller
{

    public function index()
    {
        $data = hrProd::select('tgl_prod', DB::raw('COUNT(id) as total_produksi'))
            ->groupBy('tgl_prod')
            ->orderBy('tgl_prod', 'desc') // Urutkan dari tanggal terbaru ke lama
            ->limit(35)->get();

        return view('produksi.targetharian.index', compact('data'));
    }
    public function create(Request $request)
    {
        $tgl = $request->tanggal; // Ambil tanggal dari request

        // Cek apakah tanggal produksi sudah ada di database
        $existingProd = hrProd::where('tgl_prod', $tgl)->exists();

        if ($existingProd) {
            // Jika sudah ada, redirect ke halaman edit dengan membawa tanggal tersebut
            return redirect()->route('ad.hrprod.edit', ['tgl_prod' => $tgl])
                ->with('info', 'Data produksi untuk tanggal ini sudah ada, Anda dialihkan ke halaman edit.');
        }

        // Jika tanggal belum ada, lanjutkan untuk create
        $lines = vmacunit::select('LINE')->where('STATUS', 'AKTIF')->orderBy('NL', 'asc')->get();

        return view('produksi.targetharian.create', compact('tgl', 'lines'));
    }


    public function store(Request $request)
    {

        // dd($request->toArray());
        // Validate the incoming request
        $request->validate([
            'tanggal' => 'required|date',
            'tgl_prod' => 'required|date',
            'lines.*.line' => 'required|string',
            'lines.*.target' => 'nullable|numeric', // Allow null or positive number
        ]);

        // Process each line's data
        foreach ($request->lines as $lineData) {
            // Save each line's data to the database
            hrProd::create([
                'line' => $lineData['line'],
                // 'tgl_prod' => $lineData['tgl_prod'],
                'target' => $lineData['target'],
                'tgl_prod' => $request->tgl_prod,
                // 'total' => 'test',
            ]);
        }

        // Redirect or return a response as necessary
        return redirect()->route('produksi.targetharian.index')->with('success', 'Production targets have been successfully submitted.');
    }

    public function edit(Request $request)
    {
        // dd($request->toArray());
        $tgl = $request->tgl_prod; // Get the date from the query parameter

        $data = hrProd::join('vmacunit', 'hr_prod.line', '=', 'vmacunit.LINE') // Ganti `line_id` dengan kolom relasi yang benar
            ->where('hr_prod.tgl_prod', $tgl)
            ->orderBy('vmacunit.NL', 'asc')
            ->select('hr_prod.*') // Ambil kolom dari hrProd
            ->get();

        // dd($tgl);
        return view('produksi.targetharian.edit', compact('tgl', 'data'));
    }

    public function update(Request $request, $tgl_prod)
    {
        // Validasi input dari form
        $request->validate([
            'line.*' => 'required|string|max:255',
            'target.*' => 'nullable|numeric|min:0',
        ]);

        try {
            // Ambil line dan target dari input
            $lines = $request->input('line');
            $targets = $request->input('target');

            foreach ($lines as $index => $line) {
                // Cari data berdasarkan tgl_prod dan line
                $prod = hrProd::where('tgl_prod', $tgl_prod)
                    ->where('line', $line)
                    ->first();

                if ($prod) {
                    // Update target jika datanya ada
                    $prod->target = $targets[$index] ?? null; // null jika tidak ada target
                    $prod->save();
                }
            }

            // Jika berhasil, tambahkan pesan sukses
            return redirect()->route('produksi.targetharian.index')->with('success', 'Data produksi berhasil diupdate.');
        } catch (\Exception $e) {
            // Jika terjadi error, tambahkan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate data.');
        }
    }


    public function destroy($tgl_prod)
    {
        // Mencari semua data yang memiliki tgl_prod sama
        $hrProd = HrProd::where('tgl_prod', $tgl_prod);

        // Jika data ditemukan, hapus semua data tersebut
        if ($hrProd->exists()) {
            $hrProd->delete(); // Menghapus semua record dengan tgl_prod tersebut

            // Redirect kembali dengan pesan sukses
            return redirect()->route('produksi.targetharian.index')->with('success', 'Semua data dengan tanggal produksi tersebut berhasil dihapus.');
        } else {
            // Jika data tidak ditemukan, kembali dengan pesan error
            return redirect()->route('produksi.targetharian.index')->with('error', 'Data tidak ditemukan.');
        }
    }
}
