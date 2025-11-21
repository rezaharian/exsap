<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\stokbb;
use App\Models\superker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SisaStokBahanBakuController extends Controller
{

    public function index()
    {
        $data = stokbb::orderBy('id', 'desc')->get()
            ->groupBy('tanggal') // Mengelompokkan data berdasarkan tanggal
            ->map(function ($group) {
                return [
                    'tanggal' => $group->first()->tanggal, // Ambil tanggal dari grup
                    'count_spk_nomor' => $group->count(), // Hitung jumlah SPK
                    'total_sisa_bb' => $group->sum('sisa_bb') // Total Sisa BB
                ];
            });
        // dd($data->toArray());

        return view('produksi.sisastokbahanbaku.index', compact('data'));
    }

    public function create(Request $request)
    {
        $tanggal = $request->tanggal;
        $spk = superker::select('SPK_NOMOR', 'LINE')
            ->orderBy('SPK_TGL', 'DESC')
            ->limit(800)
            ->get();
        // Cek apakah tanggal sudah ada di database
        $existingRecord = stokbb::where('tanggal', $tanggal)->first();

        if ($existingRecord) {
            // Jika sudah ada data dengan tanggal yang sama, redirect ke halaman edit
            return redirect()->route('produksi.sisastokbahanbaku.edit', ['tanggal' => $tanggal])
                ->with('error', "Data pada tanggal {$tanggal} sudah ada, silakan edit data.");
        }

        // dd($tanggal);
        // Jika tidak ada, lanjutkan ke form create untuk menambahkan data
        return view('produksi.sisastokbahanbaku.create', compact('tanggal', 'spk'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal' => 'required|date', // Validasi tanggal produksi
            'spk_nomor' => 'required|array', // Pastikan spk_nomor adalah array
            'spk_nomor.*' => 'required|string', // Setiap item spk_nomor harus string
            'sisa_bb' => 'required|array', // Pastikan sisa_bb adalah array
            'sisa_bb.*' => 'required', // Setiap item sisa_bb harus numerik
            'rp_bb' => 'required|array', // Pastikan sisa_bb adalah array
            'rp_bb.*' => 'required', // Setiap item sisa_bb harus numerik
        ]);

        // Loop untuk menyimpan data Sisa Stok Bahan Baku
        foreach ($request->spk_nomor as $key => $spk_nomor) {
            // Menyimpan data setiap baris ke database
            Stokbb::create([
                'spk_nomor' => $spk_nomor,
                'tanggal' => $request->tanggal, // Menggunakan tanggal yang dipilih oleh user
                'sisa_bb' => $request->sisa_bb[$key], // Mengambil nilai sisa_bb yang sesuai dengan baris
                'rp_bb' => $request->rp_bb[$key], // Mengambil nilai sisa_bb yang sesuai dengan baris
            ]);
        }

        // Redirect kembali dengan pesan sukses
        return redirect()->route('produksi.sisastokbahanbaku.index')  // Redirect ke halaman index
            ->with('success', 'Data Sisa Stok Bahan Baku berhasil disimpan!');
    }

    public function edit($tanggal)
    {
        $data = stokbb::orderBy('id', 'desc')
            ->where('tanggal', $tanggal)->get();
        $spk = superker::select('SPK_NOMOR', 'LINE')
            ->orderBy('SPK_TGL', 'DESC')
            ->limit(800)
            ->get();

        if (!$data) {
            return redirect()->route('produksi.sisastokbahanbaku.index')->with('error', 'Data tidak ditemukan.');
        }

        // dd($data->toArray());

        return view('produksi.sisastokbahanbaku.edit', compact('data', 'tanggal', 'spk'));
    }

    public function update(Request $request, $tanggal)
    {
        // Validasi input
        $request->validate([
            'spk_nomor' => 'required|array',
            'spk_nomor.*' => 'required|string',
            'sisa_bb' => 'required|array',
            'sisa_bb.*' => 'required',
            'rp_bb' => 'required|array', // Pastikan sisa_bb adalah array
            'rp_bb.*' => 'required', // Setiap item sisa_bb harus numerik
        ]);

        // Menghapus semua data yang memiliki tanggal yang sama
        Stokbb::where('tanggal', $tanggal)->delete();

        // Proses penyimpanan data baru
        foreach ($request->spk_nomor as $key => $spk_nomor) {
            // Menyimpan data baru berdasarkan input
            Stokbb::create([
                'spk_nomor' => $spk_nomor,
                'tanggal' => $tanggal,  // Gunakan tanggal yang sama untuk update
                'sisa_bb' => $request->sisa_bb[$key],
                'rp_bb' => $request->rp_bb[$key], // Mengambil nilai sisa_bb yang sesuai dengan baris

            ]);
        }

        // Redirect dengan pesan sukses
        return redirect()->route('produksi.sisastokbahanbaku.index')
            ->with('success', 'Data Sisa Stok Bahan Baku berhasil diperbarui!');
    }


    public function destroy($tanggal)
    {
        // Mencari semua record berdasarkan tanggal
        $stokbbRecords = Stokbb::where('tanggal', $tanggal);

        // Jika tidak ada data dengan tanggal yang diberikan
        if ($stokbbRecords->count() === 0) {
            return redirect()->route('produksi.sisastokbahanbaku.index')
                ->with('error', "Tidak ada data dengan tanggal {$tanggal} untuk dihapus.");
        }

        // Menghapus semua record dengan tanggal yang sesuai
        $stokbbRecords->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('produksi.sisastokbahanbaku.index')
            ->with('success', "Semua data dengan tanggal {$tanggal} telah dihapus.");
    }

    public function spkfromview(Request $request)
    {
        $spk = $request->input('spk_nomor');
        $tanggal = $request->input('tanggal');

        // return response()->json([
        //     'spk' => $spk,
        //     'tanggal' => $tanggal
        // ]);



        $result1 = DB::table('outgds_d')
            ->select('OUTGDSGCOD', 'OUTGDS_SPK', 'OUTGDS_PRC', 'OUTGDS_DAT')
            ->where('OUTGDS_SPK', $spk)
            ->where('OUTGDS_DAT', '<=', $tanggal)
            ->where('OUTGDSGGRP', 'A.01.')
            ->orderByDesc('OUTGDS_DAT')
            ->limit(1)
            ->first();

        $result = $result1 ?? DB::table('outgds_d')
            ->select('OUTGDSGCOD', 'OUTGDS_SPK', 'OUTGDS_PRC', 'OUTGDS_DAT')
            ->where('OUTGDS_SPK', $spk)
            ->where('OUTGDS_DAT', '>=', $tanggal)
            ->where('OUTGDSGGRP', 'A.01.')
            ->orderBy('OUTGDS_DAT', 'asc')
            ->limit(1)
            ->first();




        // Jika hasil ditemukan, kembalikan respons JSON
        if ($result) {
            return response()->json([
                'message' => 'Data ditemukan',
                'data' => $result,
            ]);
        } else {
            return response()->json([
                'message' => 'Data tidak ditemukan',
                'data' => null
            ]);
        }
    }
}
