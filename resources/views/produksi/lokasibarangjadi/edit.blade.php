@extends('layouts.app')

@section('content')
    <div class="container">

        <h3>Edit Lokasi Barang Jadi</h3>
        <a href="{{ route('produksi.lokasibarangjadi.index') }}" class="btn btn-sm btn-secondary mb-3">Kembali</a>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success small">{{ session('success') }}</div>
        @endif

        <form
            action="{{ route('produksi.lokasibarangjadi.update', [
                'spk' => $spk,
                'qty' => $qty,
            ]) }}"
            method="POST">
            @csrf
            @method('PUT')

            {{-- Data SPK --}}
            <div class="row">
                <div class="col-md-3">
                    <label>PRODUC INT</label>
                    <input type="text" name="produc_int" class="form-control form-control-sm"
                        value="{{ $data->PRODUC_INT }}" required>
                </div>

                <div class="col-md-3">
                    <label>PRODUC COD</label>
                    <input type="text" name="produc_cod" class="form-control form-control-sm"
                        value="{{ $data->PRODUC_COD }}" required>
                </div>

                <div class="col-md-3">
                    <label>PRODUC NAM</label>
                    <input type="text" name="produc_nam" class="form-control form-control-sm"
                        value="{{ $data->PRODUC_NAM }}" required>
                </div>

                <div class="col-md-3">
                    <label>SPK NOMOR</label>
                    <input type="text" name="spk_nomor" class="form-control form-control-sm"
                        value="{{ $data->SPK_NOMOR }}" required>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-4">
                    <label>Lokasi</label>
                    <input type="text" name="lokasi" class="form-control form-control-sm" value="{{ $data->LOKASI }}"
                        required>
                </div>

                <div class="col-md-4">
                    <label>Gudang</label>
                    <select name="gudang" class="form-control form-control-sm" required>
                        <option value="Produksi" {{ $data->GUDANG == 'Produksi' ? 'selected' : '' }}>Produksi</option>
                        <option value="Proses" {{ $data->GUDANG == 'Proses' ? 'selected' : '' }}>Proses</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Qty</label>
                    <input type="number" name="qty" min="1" class="form-control form-control-sm"
                        value="{{ $data->QTY }}" required>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4">
                    <label>Status</label>
                    <input type="text" name="status" class="form-control form-control-sm" value="{{ $data->STATUS }}"
                        required>
                </div>

                <div class="col-md-4">
                    <label>Tanggal</label>
                    <input type="datetime-local" name="tanggal" class="form-control form-control-sm"
                        value="{{ $data->TGL_ST }}" required>
                </div>
            </div>

            <button class="btn btn-primary btn-sm mt-4">Update</button>
        </form>
    </div>
@endsection
