<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\Locaprod;
use App\Models\superker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LokasiBarangJadiController extends Controller
{

    public function index()
    {

        return view('produksi.lokasibarangjadi.index');
    }
    public function create()
    {

        return view('produksi.lokasibarangjadi.create');
    }


    public function searchSpk(Request $request)
    {
        $search = $request->get('q');

        $data = Locaprod::where('SPK_NOMOR', 'LIKE', '%' . $search . '%')
            // ->orderBy('TGL_PROD', 'DESC')
            ->limit(50)
            ->get();


        $results = [];
        foreach ($data as $d) {
            $results[] = [
                'id'         => $d->PRODUC_INT,
                'text'       => $d->SPK_NOMOR,
                'produc_int' => $d->PRODUC_INT,
                'produc_cod' => $d->PRODUC_COD,
                'produc_nam' => $d->PRODUC_NAM,
                'spk_nomor'  => $d->SPK_NOMOR,
                'lokasi'     => $d->LOKASI,
                'gudang'     => $d->GUDANG,
                'qty'     => $d->QTY,
            ];
        }

        return response()->json($results);
    }
    public function searchSpksuperker(Request $request)
    {
        $search = $request->get('q');

        $data = superker::where('SPK_NOMOR', 'LIKE', '%' . $search . '%')
            ->orderBy('SPK_TGL', 'DESC')
            ->limit(50)
            ->get();


        $results = [];
        foreach ($data as $d) {
            $results[] = [
                'id'         => $d->id,
                'text'       => $d->SPK_NOMOR,
                'produc_int' => $d->PRODUC_INT,
                'produc_cod' => $d->PRODUC_COD,
                'produc_nam' => $d->PRODUC_NAM,
                'spk_nomor'  => $d->SPK_NOMOR,

            ];
        }

        return response()->json($results);
    }


    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'produc_int' => 'required',
            'produc_cod' => 'required',
            'produc_nam' => 'required',
            'spk_nomor' => 'required',
            'status' => 'required',
            'tanggal' => 'required|date',
            'lokasi'     => 'required|string|max:50',
            'gudang'     => 'required|string|max:50',
            'qty'        => 'required|numeric|min:1',
        ]);

        // dd($validated);
        // Simpan ke tabel input_lokasi_barang_jadi
        Locaprod::create([
            'PRODUC_INT' => $validated['produc_int'],
            'PRODUC_COD' => $validated['produc_cod'],
            'PRODUC_NAM' => $validated['produc_nam'],
            'LOKASI'     => $validated['lokasi'],
            'STATUS'     => $validated['status'],
            'TGL_ST'     => $validated['tanggal'],
            'TGL_PROD'     => $validated['tanggal'],
            'GUDANG'     => $validated['gudang'],
            'QTY'        => $validated['qty'],
            'SPK_NOMOR'        => $validated['spk_nomor'],

        ]);

        return redirect()
            ->route('produksi.lokasibarangjadi.index')
            ->with('success', 'Data lokasi barang jadi berhasil disimpan!');
    }

    public function edit($spk, $qty)
    {
        $spk = urldecode($spk);

        $data = locaprod::where('SPK_NOMOR', $spk)
            ->where('QTY', $qty)
            ->firstOrFail();
        // dd($data->toArray());
        return view('produksi.lokasibarangjadi.edit', compact('data', 'spk', 'qty'));
    }

    public function update(Request $request, $spk, $qty)
    {
        // decode SPK jika mengandung slash
        $spk = urldecode($spk);

        // Validasi input
        $validated = $request->validate([
            'produc_int' => 'required',
            'produc_cod' => 'required',
            'produc_nam' => 'required',
            'spk_nomor' => 'required',
            'status' => 'required',
            'tanggal' => 'required|date',
            'lokasi'     => 'required|string|max:50',
            'gudang'     => 'required|string|max:50',
            'qty'        => 'required|numeric|min:1',
        ]);

        // CARI DATA BERDASARKAN SPK + QTY
        locaprod::where('SPK_NOMOR', $spk)
            ->where('QTY', $qty)
            ->update([
                'PRODUC_INT' => $validated['produc_int'],
                'PRODUC_COD' => $validated['produc_cod'],
                'PRODUC_NAM' => $validated['produc_nam'],
                'LOKASI'     => $validated['lokasi'],
                'STATUS'     => $validated['status'],
                'TGL_ST'     => $validated['tanggal'],
                'TGL_PROD'   => $validated['tanggal'],
                'GUDANG'     => $validated['gudang'],
                'QTY'        => $validated['qty'],
                'SPK_NOMOR'  => $validated['spk_nomor'],
            ]);


        return redirect()
            ->route('produksi.lokasibarangjadi.index')
            ->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($spk, $qty)
    {
        try {
            $deleted = DB::table('locaprod') // nama table sesuaikan
                ->where('SPK_NOMOR', $spk)
                ->where('QTY', $qty)
                ->delete();

            if ($deleted) {
                return response()->json(['success' => 'Data berhasil dihapus']);
            } else {
                return response()->json(['error' => 'Data tidak ditemukan'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
