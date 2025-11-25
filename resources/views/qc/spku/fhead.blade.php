<style>
    #spkList div:hover {
        background-color: #f0f0f0;
    }

    .form-label-small {
        width: 35%;
        margin-bottom: 0;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .form-control-small,
    .form-control-small {
        width: 65%;
        font-size: 0.85rem;
        padding: 2px 6px;
    }

    .gap-row {
        margin-bottom: 8px;
    }
</style>

<div class="row g-3">
    {{-- KOLOM KIRI --}}
    <div class="col-md-6">
        <div class="d-flex align-items-center gap-row">
            <label class="form-label-small">No SPKU</label>
            <input type="text" name="spku_cod" value="{{ $newSpkuCode }}" class="form-control form-control-small">
        </div>

        <div class="d-flex align-items-center gap-row">
            <label class="form-label-small">No SPK</label>
            <select name="spk_nomor" id="spk_nomor" class="form-control form-control-small">
                <option value="">-- Pilih No SPK --</option>
            </select>
        </div>

        <div class="d-flex align-items-center gap-row">
            <label class="form-label-small">Nama Produk</label>
            <input type="text" name="produc_nam" readonly class="form-control form-control-small">
        </div>

        <div class="d-flex align-items-center gap-row">
            <label class="form-label-small">Nama Customer</label>
            <input type="text" name="custom_nam" readonly class="form-control form-control-small">
        </div>

        <div class="d-flex align-items-center gap-row">
            <label class="form-label-small">Line</label>
            <input type="text" name="line" readonly class="form-control form-control-small">
        </div>

        <div class="d-flex align-items-center gap-row">
            <label class="form-label-small">Ukuran Produk</label>
            <input type="text" name="produc_uk" readonly class="form-control form-control-small">
        </div>
        <div class="d-flex align-items-center gap-row">
            <label class="form-label-small">Keterangan</label>
            <textarea name="keterangan" class="form-control form-control-small"></textarea>
        </div>
    </div>

    {{-- KOLOM KANAN --}}
    <div class="col-md-6">
        <div class="d-flex align-items-center gap-row">
            <label class="form-label-small">Unit</label>
            <select name="unit" class="form-control form-control-small" required>
                <option value="">-- Pilih Unit --</option>
                <option value="Extruder & Drad">Extruder & Drad</option>
                <option value="BC & Printing">BC & Printing</option>
                <option value="Anneling & IC">Anneling & IC</option>
                <option value="Finishing">Finishing</option>
            </select>
        </div>

        <div class="d-flex align-items-center gap-row">
            <label class="form-label-small">Jenis LPKU</label>
            <select name="jn_lpku" class="form-control form-control-small" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="random">Random</option>
                <option value="proses">Proses</option>
            </select>
        </div>

        <div class="d-flex align-items-center gap-row">
            <label class="form-label-small">Tanggal Input</label>
            <input type="date" name="tgl_input" class="form-control form-control-small" required>
        </div>

        <div class="d-flex align-items-center gap-row">
            <label class="form-label-small">Shift</label>
            <select name="shift" class="form-control form-control-small" required>
                <option value="">-- Pilih Shift --</option>
                <option value="I">I</option>
                <option value="II">II</option>
                <option value="III">III</option>
            </select>
        </div>

        <div class="d-flex align-items-center gap-row">
            <label class="form-label-small">Jam</label>
            <input type="time" name="jam" class="form-control form-control-small" required>
        </div>


        <div class="d-flex align-items-center gap-row">
            <label class="form-label-small">Operator</label>
            <select name="operator" id="operator" class="form-control form-control-small" required>
                <option value="">-- Pilih Operator --</option>
            </select>
        </div>
        <div class="d-flex align-items-center gap-row">
            <label class="form-label-small">Di laporkan</label>
            <select name="dilaporkan" id="dilaporkan" class="form-control form-control-small" required>
                <option value="">-- Pilih Pelapor --</option>
            </select>
        </div>
    </div>
</div>


@section('script')
    <script>
        $(document).ready(function() {

            // =========================
            // Select2 No SPK
            // =========================
            $('#spk_nomor').select2({
                placeholder: '-- Pilih No SPK --',
                allowClear: true,
                width: 'resolve',
                minimumInputLength: 2,
                ajax: {
                    url: "{{ route('autocomplete.spk') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term,
                            type: 'spk'
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(item => ({
                                id: item.SPK_NOMOR,
                                text: item.SPK_NOMOR,
                                produc_nam: item.produc_nam,
                                custom_nam: item.custom_nam,
                                line: item.line,
                                produc_uk: item.produc_uk
                            }))
                        };
                    },
                    cache: true
                }
            });

            // Auto-fill field saat No SPK dipilih
            $('#spk_nomor').on('select2:select', function(e) {
                var data = e.params.data;
                $('input[name="produc_nam"]').val(data.produc_nam || '');
                $('input[name="custom_nam"]').val(data.custom_nam || '');
                $('input[name="line"]').val(data.line || '');
                $('input[name="produc_uk"]').val(data.produc_uk || '');
            });

            // =========================
            // Select2 Operator
            // =========================
            $('select[name="operator"]').select2({
                placeholder: '-- Pilih Operator --',
                allowClear: true,
                width: 'resolve',
                minimumInputLength: 2,
                ajax: {
                    url: "{{ route('autocomplete.spk') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term,
                            type: 'pegawai'
                        };
                    },
                    processResults: function(data) {
                        // Pastikan data sudah berupa array {id, text}
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });

            $('select[name="dilaporkan"]').select2({
                placeholder: '-- Pilih Pelapor --',
                allowClear: true,
                width: 'resolve',
                minimumInputLength: 2,
                ajax: {
                    url: "{{ route('autocomplete.spk') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term,
                            type: 'pegawai'
                        };
                    },
                    processResults: function(data) {
                        // Pastikan data sudah berupa array {id, text}
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });

            // Fokus otomatis saat Select2 dibuka
            $(document).on('select2:open', () => {
                const searchField = document.querySelector(
                    '.select2-container--open .select2-search__field');
                if (searchField) searchField.focus();
            });

        });
    </script>
@endsection
