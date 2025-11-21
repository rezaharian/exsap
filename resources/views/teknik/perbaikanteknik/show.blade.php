@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-bold text-secondary">
                    <i class="bi bi-info-circle me-2 text-primary"></i> Detail Kerusakan
                </h5>
            </div>

            <div class="card-body bg-light">

                <!-- Informasi Utama -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-white shadow-sm rounded-3 h-100">
                            <h6 class="fw-bold text-muted mb-2">Informasi Mesin</h6>
                            <p class="mb-1"><strong>Tanggal:</strong>
                                {{ \Carbon\Carbon::parse($data->tgl)->translatedFormat('d F Y') }}</p>
                            <p class="mb-1"><strong>Lokasi Line:</strong> {{ $data->lokasi_line }}</p>
                            <p class="mb-1"><strong>Kode Mesin:</strong> {{ $data->no_mesin ?? '-' }}</p>
                            <p class="mb-0"><strong>Nama Mesin:</strong> {{ $data->nama_mesin ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white shadow-sm rounded-3 h-100">
                            <h6 class="fw-bold text-muted mb-2">Klasifikasi & Pelaksana</h6>
                            <p class="mb-1"><strong>Klasifikasi:</strong> {{ $data->klasifikasi ?? '-' }}</p>
                            <p class="mb-0"><strong>Pelaksana:</strong> {{ $data->pelaksana ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Sparepart -->
                <div class="p-3 bg-white shadow-sm rounded-3 mb-3">
                    <h6 class="fw-bold text-muted mb-3">Material / Sparepart Digunakan</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th style="width:70%">Material / Sparepart</th>
                                    <th style="width:30%">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($spareparts as $spare)
                                    <tr>
                                        <td>{{ $spare->nama_sparepart ?? '-' }}</td>
                                        <td class="text-end">{{ $spare->jumlah ?? 0 }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">Tidak ada data sparepart</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Masalah & Tindakan -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-white shadow-sm rounded-3 h-100">
                            <h6 class="fw-bold text-muted mb-2">Deskripsi Masalah</h6>
                            <p class="mb-0">{{ $data->deskripsi_masalah }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white shadow-sm rounded-3 h-100">
                            <h6 class="fw-bold text-muted mb-2">penyebab</h6>
                            <p class="mb-0">{{ $data->keterangan ?? '-' }}</p>
                        </div>
                    </div>

                </div>

                <!-- Waktu & Keterangan -->
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 bg-white shadow-sm rounded-3 h-100">
                            <h6 class="fw-bold text-muted mb-2">Waktu</h6>
                            <p class="mb-1"><strong>Mulai:</strong> {{ $data->waktu_mulai ?? '-' }}</p>
                            <p class="mb-1"><strong>Selesai:</strong> {{ $data->waktu_selesai ?? '-' }}</p>
                            <p class="mb-0"><strong>Durasi:</strong> {{ $data->durasi_jam ?? 0 }} Jam</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white shadow-sm rounded-3 h-100">
                            <h6 class="fw-bold text-muted mb-2">Tindakan Perbaikan</h6>
                            <p class="mb-0">{{ $data->tindakan_perbaikan ?? '-' }}</p>
                        </div>
                    </div>

                </div>

                <!-- Tombol -->
                <div class="mt-4">
                    <a href="{{ route('teknik.perbaikanteknik.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

            </div>
        </div>
    </div>

    <style>
        body {
            background-color: #f8f9fa;
        }

        h6 {
            font-size: 0.9rem;
        }

        p {
            font-size: 0.85rem;
        }
    </style>
@endsection
