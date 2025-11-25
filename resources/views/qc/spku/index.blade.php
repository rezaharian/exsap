@extends('layouts.app')

@section('content')
    <div class="">

        {{-- 🔔 Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success small d-flex align-items-center auto-hide-alert" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger small d-flex align-items-center auto-hide-alert" role="alert">
                <i class="bi bi-x-circle-fill me-2"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-warning small d-flex align-items-start auto-hide-alert" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                <ul class="mb-0 flex-grow-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif




        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0"> DAFTAR SURAT PENYIMPANGAN KUALITAS (SPKu)</h6>
            @can('spku_view')
                <a href="{{ route('qc.spku.laporan') }}" class="btn btn-sm btn-success">Rekap SPKu</a>
            @endcan
            @can('spku_create')
                <a href="{{ route('qc.spku.create') }}" class="btn btn-sm btn-primary">+ Tambah Data</a>
            @endcan
        </div>

        {{-- 🔹 Tabel SPKu --}}
        <div class="table-responsive">
            <table id="spkuTable" class="table table-bordered table-sm align-middle small datatable">
                <thead class="table-light text-center align-middle">
                    <tr style="white-space: nowrap;">
                        <th>No</th>
                        <th>Kode</th>
                        <th>No SPK</th>
                        <th>Produk</th>
                        <th>Customer</th>
                        <th>Line</th>
                        <th>Shift</th>
                        <th>Tgl Input</th>

                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($spku as $item)
                        <tr style="white-space: nowrap;">
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->spku_cod }}</td>
                            <td>{{ $item->spk_nomor }}</td>
                            <td>{{ $item->produc_nam }}</td>
                            <td>{{ $item->custom_nam }}</td>
                            <td>{{ $item->line }}</td>
                            <td class="text-center">{{ $item->shift }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($item->tgl_input)->format('d-m-Y') }}</td>

                            <td class="text-center">
                                <a href="{{ route('qc.spku.show', $item->id) }}"
                                    class="btn btn-sm btn-outline-primary py-0 px-1">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#spkuTable').DataTable({
                pageLength: 10,
                ordering: true,
                order: [
                    [1, 'desc']
                ],
                language: {
                    search: "🔍 Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    paginate: {
                        previous: "«",
                        next: "»"
                    },
                    zeroRecords: "Tidak ada data ditemukan"
                },
                columnDefs: [{
                        targets: -1,
                        orderable: false,
                        searchable: false
                    } // kolom aksi tidak bisa diurutkan
                ]
            });
        });
    </script>

    {{-- Script untuk auto hide --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.auto-hide-alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    // fade out
                    alert.style.transition = "opacity 0.5s ease";
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500); // hapus dari DOM setelah fade
                }, 4000); // 4 detik
            });
        });
    </script>
@endsection
