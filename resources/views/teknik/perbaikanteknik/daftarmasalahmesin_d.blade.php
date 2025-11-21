@extends('layouts.app')

@section('content')

    <div class="container text-xs px-4">
        <div class="row">
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (Session::has('success'))
                <div class="alert alert-success text-center">
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif
        </div>

        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <td><label for="prob_cod" class="form-control-label">NO Doc*</label></td>
                        <td><input disabled class="form-control" type="text" name="prob_cod"
                                value="{{ $view->prob_cod }}"></td>
                    </tr>
                    <tr>
                        <td><label for="tgl_input" class="form-control-label">Tanggal</label></td>
                        <td><input disabled class="form-control" type="text" name="tgl_input"
                                value="{{ $view->tgl_input }}" required></td>
                    </tr>
                    <tr>
                        <td><label for="masalah" class="form-control-label">Masalah</label></td>
                        <td>
                            <textarea disabled class="form-control" name="masalah" required>{{ $view->masalah }}</textarea>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="penyebab" class="form-control-label">Penyebab</label></td>
                        <td>
                            <textarea disabled class="form-control" name="penyebab" required>{{ $view_d->penyebab }}</textarea>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="tgl_rpr" class="form-control-label">Tgl Perbaikan</label></td>
                        <td><input disabled class="form-control" type="text" name="tgl_rpr"
                                value="{{ $view_d->tgl_rpr }}"></td>
                    </tr>
                    <tr>
                        <td><label for="perbaikan" class="form-control-label">Perbaikan</label></td>
                        <td>
                            <textarea disabled class="form-control" name="perbaikan" required>{{ $view_d->perbaikan }}</textarea>
                        </td>
                    </tr>

                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-bordered">

                    <tr>
                        <td><label for="tgl_pre" class="form-control-label">Tgl Pencegahan</label></td>
                        <td><input disabled class="form-control" type="text" name="tgl_pre"
                                value="{{ $view_d->tgl_pre }}"></td>
                    </tr>
                    <tr>
                        <td><label for="pencegahan" class="form-control-label">Pencegahan</label></td>
                        <td>
                            <textarea disabled class="form-control" name="pencegahan" required>{{ $view_d->pencegahan }}</textarea>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="line" class="form-control-label">Line</label></td>
                        <td>
                            <select disabled class="form-select" name="line">
                                <option selected>{{ $view->line }}</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="unitmesin" class="form-control-label">Unit Mesin</label></td>
                        <td>
                            <select disabled class="form-select" name="unitmesin">
                                <option selected>{{ $view->unitmesin }}</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="modal-body mt-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <a href="/image/{{ $view->img_pro01 }}" target="_blank">
                        <img src="/image/{{ $view->img_pro01 }}" alt="Image 1" class="img-fluid">
                    </a>
                </div>
                <div class="col-md-6 mb-3">
                    <a href="/image/{{ $view->img_pro02 }}" target="_blank">
                        <img src="/image/{{ $view->img_pro02 }}" alt="Image 2" class="img-fluid">
                    </a>
                </div>
                <div class="col-md-6 mb-3">
                    <a href="/image/{{ $view->img_pro03 }}" target="_blank">
                        <img src="/image/{{ $view->img_pro03 }}" alt="Image 3" class="img-fluid">
                    </a>
                </div>
                <div class="col-md-6 mb-3">
                    <a href="/image/{{ $view->img_pro04 }}" target="_blank">
                        <img src="/image/{{ $view->img_pro04 }}" alt="Image 4" class="img-fluid">
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
