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

        <h3>Manage Lokasi Barang Jadi</h3>
        @can('lokasi_barang_jadi_create')
            <a href="{{ route('produksi.lokasibarangjadi.create') }}"><button class="btn btn-sm btn-primary">Tambah
                    Lokasi</button></a>
        @endcan

        <br>

        <label class="mt-3">Cari SPK</label>
        <select id="search_spk1" class="form-control form-control-sm" style="max-width: 400px;"></select>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>PRODUC INT</th>
                    <th>PRODUC COD</th>
                    <th>PRODUC NAM</th>
                    <th>SPK NOMOR</th>
                    <th>LOKASI</th>
                    <th>GUDANG</th>
                    <th>QTY</th>
                    <th width="140">AKSI</th>
                </tr>
            </thead>
            <tbody id="spk-results">
                <!-- Data SPK akan muncul di sini -->
            </tbody>
        </table>
    </div>
@endsection
<!-- Select2 CDN -->
@section('script')
    <script>
        $(document).ready(function() {

            // ================= SELECT2 =================
            $('#search_spk1').select2({
                placeholder: 'Ketik nomor SPK...',
                minimumInputLength: 1,
                width: '100%',
                ajax: {
                    url: "{{ route('search.spk') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                }
            });

            $('#search_spk1').on('select2:open', function() {
                document.querySelector('.select2-search__field').focus();
            });

            // ================= SELECT SPK =================
            $('#search_spk1').on('select2:select', function(e) {
                let d = e.params.data;

                let row = `
            <tr>
                <td>${d.produc_int ?? ''}</td>
                <td>${d.produc_cod ?? ''}</td>
                <td>${d.produc_nam ?? ''}</td>
                <td>${d.spk_nomor ?? ''}</td>
                <td>${d.lokasi ?? ''}</td>
                <td>${d.gudang ?? ''}</td>
                <td>${d.qty ?? ''}</td>
                <td>
                    <a href="/admin/dashboard/inputlokasibarangjadi/${encodeURIComponent(d.spk_nomor)}/${d.qty}/edit" 
                       class="btn btn-sm btn-warning">Edit</a>

                    <button type="button" class="btn btn-sm btn-danger btn-hapus"
                        data-spk="${d.spk_nomor}"
                        data-qty="${d.qty}">
                        Hapus
                    </button>
                </td>
            </tr>
        `;

                $("#spk-results").html(row);
            });

            // ================= HAPUS =================
            $(document).on("click", ".btn-hapus", function(e) {
                e.preventDefault();

                let spk = $(this).data("spk");
                let qty = $(this).data("qty");

                if (!confirm("Yakin ingin menghapus data ini?")) return;

                let url = `/admin/dashboard/inputlokasibarangjadi/${encodeURIComponent(spk)}/${qty}`;

                $.ajax({
                    url: url,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}" // jangan lupa csrf token
                    },
                    success: function(res) {
                        alert(res.success || "Data berhasil dihapus");
                        location.reload();
                    },
                    error: function(xhr) {
                        console.log(xhr); // cek network tab / console
                        alert("Gagal menghapus data: " + (xhr.responseJSON?.error || xhr
                            .statusText));
                    }
                });
            });

        });
    </script>
@endsection
