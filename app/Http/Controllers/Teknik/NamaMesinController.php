<?php

namespace App\Http\Controllers\Teknik;

use App\Http\Controllers\Controller;
use App\Models\NamaMesin;
use Illuminate\Http\Request;

class NamaMesinController extends Controller
{
    public function index()
    {
        $data = NamaMesin::orderBy('kode_mesin', 'desc')->get()->groupBy('kode_mesin');;
        return view('teknik.perbaikanteknik.mesin.index', compact('data'));
    }

    public function create()
    {
        // Ambil daftar kode mesin unik yang sudah ada
        $kodeMesin = NamaMesin::select('kode_mesin')->distinct()->pluck('kode_mesin');
        return view('teknik.perbaikanteknik.mesin.create', compact('kodeMesin'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'kode_mesin' => 'required|string|max:50',
            'nama_mesin' => 'required|string|max:255',
        ]);

        NamaMesin::create([
            'kode_mesin' => $request->kode_mesin,
            'nama_mesin' => $request->nama_mesin,
        ]);

        return redirect()->route('teknik.perbaikanteknik.mesin.index')->with('success', 'Mesin berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = NamaMesin::findOrFail($id);

        // Ambil daftar kode mesin unik untuk datalist
        $kodeMesin = NamaMesin::select('kode_mesin')->distinct()->pluck('kode_mesin');

        return view('teknik.perbaikanteknik.mesin.edit', compact('item', 'kodeMesin'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_mesin' => 'required|string|max:50',
            'nama_mesin' => 'required|string|max:255',
        ]);

        $item = NamaMesin::findOrFail($id);
        $item->update([
            'kode_mesin' => $request->kode_mesin,
            'nama_mesin' => $request->nama_mesin,
        ]);

        return redirect()->route('teknik.perbaikanteknik.mesin.index')->with('success', 'Mesin berhasil diperbarui.');
    }
    public function delete($id)
    {
        $item = NamaMesin::findOrFail($id);
        $item->delete();

        return redirect()->route('teknik.perbaikanteknik.mesin.index')->with('success', 'Mesin berhasil dihapus.');
    }


    // NamaMesinController.php
    public function getByKode($kode)
    {
        $namaMesin = NamaMesin::where('kode_mesin', $kode)->orderBy('kode_mesin', 'asc')
            ->pluck('nama_mesin');
        return response()->json($namaMesin);
    }
}
