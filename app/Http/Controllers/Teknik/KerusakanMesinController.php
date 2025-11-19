<?php

namespace App\Http\Controllers\Teknik;

use App\Models\prob_msd;
use App\Models\prob_msn;
use App\Models\unit_msn;
use App\Models\vmacunit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class KerusakanMesinController extends Controller
{


    public function index()
    {

        $prob_h = prob_msn::orderby('id', 'desc')->get();
        // dd($prob_h->toArray());
        return view('/teknik/kerusakanmesin/index', compact('prob_h'));
    }

    public function create()
    {
        $namaprofile = Auth::user();
        $datau = unit_msn::orderby('id', 'DESC')->get();
        $datal = vmacunit::orderby('id', 'DESC')->get();
        $datano = prob_msn::select('id', 'prob_cod')->orderby('prob_cod', 'desc')
            ->first();

        $thn = Carbon::now()->format('Y');
        $trakhir = $datano->prob_cod;
        $thna_p = substr($trakhir, 0, 2);

        $thns = Carbon::now()->format('Y');
        $bln = Carbon::now()->format('m');
        $thn_p = substr($thns, 2, 2);
        $trakhirconf = substr($trakhir, 2, 5);

        $trakhirconf++;
        if ($thna_p != $thn_p) {
            $f = 001;
        } else {
            // dd('p');
            $f = $trakhirconf;
        }


        $bln = Carbon::now()->format('m');
        $kode = 0001;
        $kode++;

        $nod = $bln . sprintf('%02s', $kode);

        $no = $thn_p .  sprintf('%04s', $f);

        // dd($trakhirconf);
        return view('/teknik/kerusakanmesin/create', compact('namaprofile', 'datau', 'datal', 'no', 'nod'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'prob_cod' => 'unique:prob_msns,prob_cod',
            'img_pro01' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf|max:200',
            'img_pro02' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf|max:200',
            'img_pro03' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf|max:200',
            'img_pro04' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf|max:200',
        ]);

        // Upload gambar
        $images = [];
        for ($i = 1; $i <= 4; $i++) {
            $field = 'img_pro0' . $i;
            if ($request->hasFile($field)) {
                $images[$field] = $request->file($field)->store('images', 'public');
            } else {
                $images[$field] = 'No Image';
            }
        }

        // Simpan header
        $header = prob_msn::create([
            'prob_cod'   => $request->prob_cod,
            'tgl_input'  => $request->tgl_input,
            'masalah'    => $request->masalah,
            'line'       => $request->line,
            'unitmesin'  => $request->unitmesin,
            'img_pro01'  => $images['img_pro01'],
            'img_pro02'  => $images['img_pro02'],
            'img_pro03'  => $images['img_pro03'],
            'img_pro04'  => $images['img_pro04'],
        ]);

        // Simpan detail
        foreach ($request->id_no as $key => $id_no) {
            prob_msd::create([
                'id_no'       => $id_no,
                'penyebab'    => $request->penyebab[$key],
                'perbaikan'   => $request->perbaikan[$key],
                'tgl_rpr'     => $request->tgl_rpr[$key],
                'pencegahan'  => $request->pencegahan[$key],
                'tgl_pre'     => $request->tgl_pre[$key],
                'prob_cod'    => $request->prob_cod,
                'tgl_input'   => $request->tgl_input,
            ]);
        }

        return redirect()->route('teknik.kerusakanmesin.index')
            ->with('success', 'Data kerusakan mesin berhasil ditambahkan.');
    }

    public function edit($id, Request $request)
    {
        // dd($id, $request->toArray());
        $namaprofile = Auth::user();
        $data = prob_msn::findorfail($id);
        $data_d = prob_msd::where('prob_cod', $data->prob_cod)
            ->orderby('id', 'asc')
            ->get();
        $jmlh_d = count($data_d);
        $datau = unit_msn::orderby('id', 'DESC')->get();
        $datal = vmacunit::orderby('id', 'DESC')->get();
        // dd($data);

        $bln = Carbon::now()->format('m');
        $kode = 0001;
        $kode++;
        $nod = $bln . sprintf('%02s', $kode);
        // dd('d');
        return view('/teknik/kerusakanmesin/edit', compact('data', 'data_d', 'namaprofile', 'nod', 'jmlh_d', 'datal', 'datau'));
    }
    // Update
    public function update(Request $request, $id)
    {
        $data = prob_msn::findOrFail($id);

        $request->validate([
            'img_pro01' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf|max:200',
            'img_pro02' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf|max:200',
            'img_pro03' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf|max:200',
            'img_pro04' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf|max:200',
        ]);

        // Upload gambar, overwrite hanya jika ada file baru
        $images = [];
        for ($i = 1; $i <= 4; $i++) {
            $field = 'img_pro0' . $i;
            if ($request->hasFile($field)) {
                // Hapus gambar lama jika ada dan bukan 'No Image'
                if ($data->$field && $data->$field !== 'No Image') {
                    Storage::disk('public')->delete($data->$field);
                }
                $images[$field] = $request->file($field)->store('images', 'public');
            } else {
                $images[$field] = $data->$field; // tetap gambar lama
            }
        }

        // Update header
        $data->update([
            'tgl_input'  => $request->tgl_input,
            'masalah'    => $request->masalah,
            'line'       => $request->line,
            'unitmesin'  => $request->unitmesin,
            'img_pro01'  => $images['img_pro01'],
            'img_pro02'  => $images['img_pro02'],
            'img_pro03'  => $images['img_pro03'],
            'img_pro04'  => $images['img_pro04'],
        ]);

        // Update detail
        // Hapus semua detail lama, lalu simpan ulang
        prob_msd::where('prob_cod', $data->prob_cod)->delete();
        foreach ($request->id_no as $key => $id_no) {
            prob_msd::create([
                'id_no'       => $id_no,
                'penyebab'    => $request->penyebab[$key],
                'perbaikan'   => $request->perbaikan[$key],
                'tgl_rpr'     => $request->tgl_rpr[$key],
                'pencegahan'  => $request->pencegahan[$key],
                'tgl_pre'     => $request->tgl_pre[$key],
                'prob_cod'    => $data->prob_cod,
                'tgl_input'   => $request->tgl_input,
            ]);
        }

        return redirect()->route('teknik.kerusakanmesin.index')
            ->with('success', 'Data kerusakan mesin berhasil diperbarui.');
    }

    public function delete($id)
    {
        $prob_h = prob_msn::findOrFail($id);
        $prob_d = prob_msd::where('prob_cod', $prob_h->prob_cod);
        $prob_h->delete();
        $prob_d->delete();
        return redirect()
            ->route('teknik.kerusakanmesin.index')
            ->with('success', 'Data berhasil dihapus!');
    }
    public function delete_d($id,)
    {
        $data_d = prob_msd::findOrFail($id);
        $data_d->delete();
        return back();
    }

    public function
    print($id)
    {
        $view = prob_msn::findorfail($id);
        $view_d = prob_msd::where('prob_cod', $view->prob_cod)->get();
        $pdf = PDF::loadview('/teknik/kerusakanmesin/inputlistprintd', compact('view', 'view_d'));
        return $pdf->setPaper('a4', 'potrait')->stream('Kerusakan_d.pdf');
    }
}
