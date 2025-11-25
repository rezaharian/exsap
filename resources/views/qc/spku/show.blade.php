@extends('layouts.app')

@section('content')
    <div class="">

        {{-- 🔔 Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show small">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Detail SPKu</h6>
            <div>
                @can('spku_edit')
                    <a href="{{ route('qc.spku.edit', $spku->id) }}" class="btn btn-sm btn-warning me-1">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                @endcan

                @can('spku_delete')
                    <form action="{{ route('qc.spku.destroy', $spku->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus SPKu ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger me-1">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                @endcan
                <a href="{{ route('qc.spku.index') }}" class="btn btn-sm btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        {{-- 🔹 Data Header --}}
        <div class="card mb-3">
            <div class="card-body small">
                <div class="row mb-1">
                    <div class="col-md-3 fw-bold">Kode SPKu:</div>
                    <div class="col-md-3">{{ $spku->spku_cod }}</div>
                    <div class="col-md-3 fw-bold">No SPK:</div>
                    <div class="col-md-3">{{ $spku->spk_nomor }}</div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-3 fw-bold">Produk:</div>
                    <div class="col-md-3">{{ $spku->produc_nam }}</div>
                    <div class="col-md-3 fw-bold">Customer:</div>
                    <div class="col-md-3">{{ $spku->custom_nam }}</div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-3 fw-bold">Line:</div>
                    <div class="col-md-3">{{ $spku->line }}</div>
                    <div class="col-md-3 fw-bold">Shift:</div>
                    <div class="col-md-3">{{ $spku->shift }}</div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-3 fw-bold">Tanggal Input:</div>
                    <div class="col-md-3">{{ \Carbon\Carbon::parse($spku->tgl_input)->format('d-m-Y') }}</div>
                    <div class="col-md-3 fw-bold">Jam:</div>
                    <div class="col-md-3">{{ $spku->jam }}</div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-3 fw-bold">Operator:</div>
                    <div class="col-md-3">{{ $spku->operator }}</div>
                    <div class="col-md-3 fw-bold">Jenis Laporan:</div>
                    <div class="col-md-3">{{ $spku->Jn_lpku }}</div>
                </div>
                <div class="row mb-1">
                    <div class="col-md-3 fw-bold">Keterangan:</div>
                    <div class="col-md-3">{{ $spku->keterangan }}</div>
                    <div class="col-md-3 fw-bold">Di laporkan:</div>
                    <div class="col-md-3">{{ $spku->dilaporkan }}</div>
                </div>
            </div>
        </div>

        {{-- 🔹 Tabel Detail --}}
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle small">
                <thead class="table-light text-center align-middle" style="white-space: nowrap;">
                    <tr>
                        <th>No</th>
                        {{-- <th>INT</th> --}}
                        <th>Kd Grup</th>
                        <th>Jns Penyimpangan</th>
                        <th>Penyebab</th>
                        <th>Perbaikan</th>
                        <th>Tgl Perbaikan</th>
                        <th>Pencegahan</th>
                        <th>Tgl Pre</th>

                    </tr>
                </thead>
                <tbody>
                    @forelse ($spku->details as $index => $d)
                        <tr style="white-space: nowrap;">
                            <td class="text-center">{{ $index + 1 }}</td>
                            {{-- <td class="text-center">{{ $d->int }}</td> --}}
                            <td>{{ $d->kd_grup }}</td>
                            <td>{{ $d->jn_penyimpangan }}</td>
                            <td>{{ $d->penyebab }}</td>
                            <td>{{ $d->perbaikan }}</td>
                            <td class="text-center">
                                {{ $d->tgl_perbaikan ? \Carbon\Carbon::parse($d->tgl_perbaikan)->format('d-m-Y') : '-' }}
                            </td>
                            <td>{{ $d->pencegahan }}</td>
                            <td class="text-center">
                                {{ $d->tgl_pre ? \Carbon\Carbon::parse($d->tgl_pre)->format('d-m-Y') : '-' }}</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted">Belum ada detail SPKu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
