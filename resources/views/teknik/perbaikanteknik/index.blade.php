@extends('layouts.app')

@section('content')
    <div class="container">


        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif


        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show small py-2 px-3" role="alert">
                ❌ {{ session('error') }}
                <button type="button" class="btn btn-close btn-sm py-0 px-1" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        @endif

        {{-- ✅ Header & Tombol Aksi --}}
        <div class="d-flex justify-content-between align-items-center mb-4 p-2 border-bottom">
            <h6 class="mb-0 text-muted fw-semibold d-flex align-items-center">
                <i class="fas fa-tools me-2 text-primary"></i> Data Perbaikan Teknik
            </h6>

            <div class="d-flex align-items-center gap-2">
                <!-- Tombol Export Excel -->
                {{-- <button type="button"
                    class="btn btn-outline-success btn-sm rounded-pill shadow-sm d-flex align-items-center"
                    data-bs-toggle="modal" data-bs-target="#tahunModal">
                    <i class="bi bi-file-earmark-excel me-1"></i> Excel
                </button> --}}

                {{-- <a href="{{ route('admopteknik.kerusakanteknik.laporanpertahun') }}"
                    class="btn btn-outline-primary btn-sm rounded-pill shadow-sm d-flex align-items-center">
                    <i class="fas fa-file-export me-1"></i> Laporan Downtime /Tahun
                </a> --}}

                {{-- <a href="{{ route('kerusakanteknik.laporanperline') }}"
                    class="btn btn-outline-info btn-sm rounded-pill shadow-sm d-flex align-items-center">
                    <i class="fas fa-file-export me-1"></i> Laporan Per Line
                </a> --}}

                {{-- <a href="{{ route('namamesin.index') }}"
                    class="btn btn-outline-secondary btn-sm rounded-pill shadow-sm d-flex align-items-center">
                    <i class="fas fa-cogs me-1"></i> Daftar Mesin
                </a> --}}

                @can('perbaikan_teknik_create')
                    <a href="{{ route('teknik.perbaikanteknik.create') }}"
                        class="btn btn-success btn-sm  shadow-sm d-flex align-items-center">
                        <i class="fas fa-plus me-1"></i> Tambah
                    </a>
                @endcan
            </div>
        </div>

        {{-- ✅ Modal Pilih Tahun Export --}}
        {{-- <div class="modal fade" id="tahunModal" tabindex="-1" aria-labelledby="tahunModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-semibold text-secondary" id="tahunModalLabel">
                            <i class="bi bi-calendar-check me-2 text-success"></i>Pilih Tahun Export
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body text-center">
                        <form id="formExportTahun" class="d-flex flex-wrap justify-content-center gap-2 mt-2">
                            @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                <div class="form-check form-check-inline year-option">
                                    <input class="form-check-input visually-hidden" type="radio" name="tahun"
                                        id="tahun{{ $i }}" value="{{ $i }}">
                                    <label
                                        class="btn btn-outline-success rounded-pill px-3 py-1 small fw-semibold shadow-sm"
                                        for="tahun{{ $i }}">{{ $i }}</label>
                                </div>
                            @endfor
                        </form>

                        <!-- Spinner Proses -->
                        <div id="prosesExport" class="text-center d-none mt-4">
                            <div class="spinner-border text-success spinner-border-sm mb-2" role="status"></div><br>
                            <span class="small text-muted">Export Excel sedang dalam proses...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- ✅ Table Data --}}
        <div class="table-responsive">
            <table id="kerusakanTable" class="table table-bordered table-hover table-sm align-middle small mb-0 datatable">
                <thead class="table-light text-center fw-normal text-dark">
                    <tr>
                        <th>No</th>
                        <th>Tgl</th>
                        <th>Line</th>
                        <th>No Mesin</th>
                        <th>Mesin</th>
                        <th>Masalah</th>
                        <th>Perbaikan</th>
                        <th>Pelaksana</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-dark">
                    @forelse($data as $row)
                        <tr style="font-size: 7pt; color: #000;">
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($row->tgl)->format('d M Y') }}</td>
                            <td>{{ $row->lokasi_line }}</td>
                            <td>{{ $row->no_mesin }}</td>
                            <td>{{ $row->nama_mesin }}</td>
                            <td class="text-truncate" style="max-width: 120px;" title="{{ $row->deskripsi_masalah }}">
                                {{ $row->deskripsi_masalah }}
                            </td>
                            <td class="text-truncate" style="max-width: 120px;" title="{{ $row->tindakan_perbaikan }}">
                                {{ $row->tindakan_perbaikan }}
                            </td>
                            <td>{{ $row->pelaksana }}</td>
                            {{-- <td class="text-center">
                                <a href="{{ route('kerusakanteknik.show', $row->id) }}"
                                    class="btn btn-outline-info btn-xs me-1" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('kerusakanteknik.edit', $row->id) }}"
                                    class="btn btn-outline-warning btn-xs me-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('kerusakanteknik.destroy', $row->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-xs"
                                        onclick="return confirm('Yakin hapus data?')" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td> --}}
                            <td> </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted small">Belum ada data kerusakan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ✅ Styling --}}
    <style>
        /* Ukuran tombol kecil */
        .btn-xs {
            padding: .2rem .25rem;
            font-size: .65rem;
            line-height: 1;
            border-radius: .2rem;
        }

        /* Warna teks tabel jadi hitam pekat */
        #kerusakanTable th,
        #kerusakanTable td {
            color: #000 !important;
        }

        /* Penyesuaian DataTables */
        #kerusakanTable_wrapper .dataTables_length,
        #kerusakanTable_wrapper .dataTables_filter,
        #kerusakanTable_wrapper .dataTables_info,
        #kerusakanTable_wrapper .dataTables_paginate {
            font-size: 0.75rem;
            color: #000;
        }

        #kerusakanTable_wrapper .form-select,
        #kerusakanTable_wrapper .form-control {
            padding: .1rem .25rem;
            font-size: .75rem;
            height: auto;
            color: #000;
        }

        #kerusakanTable_wrapper .paginate_button {
            padding: .1rem .4rem !important;
            font-size: .7rem;
        }

        .alert {
            font-size: 0.8rem;
            margin-top: .5rem;
        }

        .btn-close {
            font-size: .6rem;
        }
    </style>

    {{-- ✅ Script --}}
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // ✅ DataTables
            $('#kerusakanTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                lengthMenu: [5, 10, 25, 50],
                pageLength: 10,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampil _MENU_ data",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    paginate: {
                        previous: "&laquo;",
                        next: "&raquo;"
                    }
                },
                columnDefs: [{
                    orderable: false,
                    targets: -1
                }],
                dom: '<"top"f>rt<"bottom"lip><"clear">'
            });

            // ✅ Export Tahun (auto-close modal)
            $('input[name="tahun"]').on('change', function() {
                const tahun = $(this).val();
                if (tahun) {
                    $('#prosesExport').removeClass('d-none');
                    $('.year-option input, .year-option label').prop('disabled', true);

                    const url = tahun = " + tahun;

                    const link = document.createElement('a');
                    link.href = url;
                    link.download = '';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    setTimeout(() => {
                        $('#tahunModal').modal('hide');
                        $('#prosesExport').addClass('d-none');
                        $('.year-option input, .year-option label').prop('disabled', false);
                        $('input[name="tahun"]').prop('checked', false);
                    }, 1000);
                }
            });
        });
    </script>
@endsection
