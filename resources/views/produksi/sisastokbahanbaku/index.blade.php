@extends('layouts.app')

@section('content')
    <div class="container card p-4 shadow-sm border-0 rounded-3">

        <!-- Pesan sukses -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Pesan error -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-10">
                <div class="text-center mb-1">
                    <h5 class="display-5 m-0 fw-bold text-primary"><strong>SISA STOK BAHAN BAKU </strong></h5>
                </div>
            </div>
            <div class="col-md-2">

                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-primary btn-msm m-0 px-2 rounded-sm shadow-sm"
                        data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="bi bi-plus-lg"></i> Create
                    </button>
                </div>
            </div>
        </div>
        <!-- Judul halaman -->

        <!-- Tombol untuk membuka modal -->
    </div>



    <div class="card mt-1 p-2">


        <!-- Ubah kode tabel Anda menjadi seperti berikut -->
        <table id="dataTable" class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr class="text-center">
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Jumlah SPK</th>
                    <th>Total Sisa BB</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $index => $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}</td>
                        <td class="text-center">{{ $item['count_spk_nomor'] }}</td>
                        <td class="text-right">{{ number_format($item['total_sisa_bb'], 1, ',', '.') }}</td>
                        <td class="text-center">
                            <!-- Tombol Edit -->
                            <a href="{{ route('produksi.sisastokbahanbaku.edit', ['tanggal' => $item['tanggal']]) }}"
                                class="btn btn-warning btn-sm">Edit</a>

                            <!-- Tombol Hapus -->
                            <form
                                action="{{ route('produksi.sisastokbahanbaku.destroy', ['tanggal' => $item['tanggal']]) }}"
                                method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data tersedia</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Input Tanggal </h5>
                </div>
                <div class="modal-body">
                    <!-- Form for date input -->
                    <form action="{{ route('produksi.sisastokbahanbaku.create') }}" method="GET">
                        @csrf <!-- Tidak perlu @csrf untuk GET, ini opsional -->
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Silahkan Pilih Tanggal Dahulu</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Inisialisasi DataTables -->
    <script>
        $(document).ready(function() {
            var table = $('#dataTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/Indonesian.json"
                },
                // "order": [
                //     [1, "desc"]
                // ], // Mengurutkan berdasarkan kolom kedua (indeks 1) secara descending
                "rowCallback": function(row, data, index) {
                    // Update nomor urut sesuai dengan urutan yang ditampilkan
                    $('td:eq(0)', row).html(index + 1);
                }
            });
        });
    </script>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
