@extends('layouts.app')

@section('content')
    <div class="container">

        <h3>Tambah Lokasi Barang Jadi</h3>
        <a href="{{ route('produksi.lokasibarangjadi.index') }}" class="btn btn-sm btn-secondary mb-3">Kembali</a>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success small">{{ session('success') }}</div>
        @endif

        <form action="{{ route('produksi.lokasibarangjadi.store') }}" method="POST">
            @csrf

            {{-- Select SPK --}}
            <label class="fw-bold">Cari SPK</label>
            <select id="search_spk" class="form-control form-control-sm" style="max-width: 400px;"></select>

            {{-- Preview SPK --}}
            <table class="table table-bordered mt-3">
                <thead class="table-light">
                    <tr>
                        <th>PRODUC INT</th>
                        <th>PRODUC COD</th>
                        <th>PRODUC NAM</th>
                        <th>SPK NOMOR</th>
                    </tr>
                </thead>
                <tbody id="preview-spk">
                    <tr>
                        <td colspan="4" class="text-center text-muted">Pilih SPK terlebih dahulu...</td>
                    </tr>
                </tbody>
            </table>

            <hr>

            {{-- Input Lokasi Baru --}}
            <div class="row">
                <div class="col-md-4">
                    <label class="fw-bold">Lokasi</label>
                    <input type="text" name="lokasi" value="E-LN 01" class="form-control form-control-sm" required>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold">Status</label>
                    <select name="status" class="form-control form-control-sm" required>
                        <option value="Produksi">Produksi</option>
                        <option value="Proses">Proses</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold">Gudang</label>
                    <select name="gudang" class="form-control form-control-sm" required>
                        <option value="Extrupack">Extrupack</option>
                        <option value="Solo">Solo</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold">Tanggal Produksi</label>
                    <input type="date" name="tanggal" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-4">
                    <label class="fw-bold">Qty</label>
                    <input type="number" name="qty" min="1" class="form-control form-control-sm" required>
                </div>
            </div>

            {{-- Hidden Input --}}
            {{-- <input type="" name="spk_id" id="spk_id"> --}}
            <input type="hidden" name="produc_int" id="produc_int">
            <input type="hidden" name="produc_cod" id="produc_cod">
            <input type="hidden" name="produc_nam" id="produc_nam">
            <input type="hidden" name="spk_nomor" id="spk_nomor">

            <button class="btn btn-primary btn-sm mt-4">Simpan</button>
        </form>
    </div>

    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {

            // Select2
            $('#search_spk').select2({
                placeholder: 'Ketik nomor SPK...',
                minimumInputLength: 1,
                width: '100%',
                ajax: {
                    url: "{{ route('search.spksuperker') }}",
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        q: params.term
                    }),
                    processResults: data => ({
                        results: data
                    }),
                }
            });

            // Autofocus saat Select2 dibuka
            $('#search_spk').on('select2:open', () => {
                setTimeout(() => {
                    document.querySelector('.select2-search__field').focus();
                }, 0);
            });

            // Saat SPK dipilih
            $('#search_spk').on('select2:select', function(e) {
                let d = e.params.data;

                $('#preview-spk').html(`
            <tr>
                <td>${d.produc_int ?? ''}</td>
                <td>${d.produc_cod ?? ''}</td>
                <td>${d.produc_nam ?? ''}</td>
                <td>${d.spk_nomor ?? ''}</td>
            </tr>
        `);

                // Hidden input
                $('#spk_id').val(d.id);
                $('#produc_int').val(d.produc_int);
                $('#produc_cod').val(d.produc_cod);
                $('#produc_nam').val(d.produc_nam);
                $('#spk_nomor').val(d.spk_nomor);
            });

        });
    </script>
@endsection
