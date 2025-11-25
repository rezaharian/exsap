<hr>
<b class="fw-bold mb-2" style="font-size: 13px;">Form Detail</b>

<style>
    #detailTable th,
    #detailTable td {
        padding: 2px 4px !important;
        font-size: 12px !important;
        vertical-align: middle;
    }

    .form-control-sm,
    textarea.form-control-sm {
        padding: 1px 3px !important;
        font-size: 12px !important;
        height: 22px !important;
    }

    textarea.form-control-sm {
        height: 38px !important;
        resize: both !important;
        min-height: 38px !important;
        max-height: 200px !important;
    }

    .btn-sm {
        padding: 2px 6px !important;
        font-size: 12px !important;
    }

    .table {
        margin-bottom: 5px !important;
    }
</style>

<div>
    <div class="table-responsive">
        <table class="table table-bordered align-middle" id="detailTable">
            <thead class="table-light text-center">
                <tr>
                    <th hidden>INT</th>
                    <th>Kd Grup</th>
                    <th>Jns Penyimpangan</th>
                    <th>Penyebab</th>
                    <th>Perbaikan</th>
                    <th>Tgl Perbaikan</th>
                    <th>Pencegahan</th>
                    <th>Tgl Pre</th>

                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td hidden><input type="text" name="int[]" class="form-control form-control-sm int-field"></td>
                    <td>
                        <select name="kd_grup[]" class="form-control form-control-sm">
                            <option value="">-- Pilih Grup --</option>
                            @foreach ($jnpn as $g)
                                <option value="{{ $g->kd_grup }}">{{ $g->penyimpangan }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <textarea name="jn_penyimpangan[]" class="form-control form-control-sm"></textarea>
                    </td>
                    <td>
                        <textarea name="penyebab[]" class="form-control form-control-sm"></textarea>
                    </td>
                    <td>
                        <textarea name="perbaikan[]" class="form-control form-control-sm"></textarea>
                    </td>
                    <td><input type="date" name="tgl_perbaikan[]" class="form-control form-control-sm"></td>
                    <td>
                        <textarea name="pencegahan[]" class="form-control form-control-sm"></textarea>
                    </td>
                    <td><input type="date" name="tgl_pre[]" class="form-control form-control-sm"></td>

                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger removeRow">Hapus</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="text-end mt-1">
        <button type="button" class="btn btn-sm btn-primary" id="addRow">+ Tambah Baris</button>
    </div>
</div>

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.querySelector('#detailTable tbody');
            const addRowBtn = document.querySelector('#addRow');

            // 🔹 Simpan kode yang sudah dipakai supaya tidak dobel
            let usedCodes = new Set();

            // 🔹 Fungsi generate kode acak unik 4 karakter
            function generateUniqueCode() {
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                let code;
                do {
                    code = '';
                    for (let i = 0; i < 4; i++) {
                        code += chars.charAt(Math.floor(Math.random() * chars.length));
                    }
                } while (usedCodes.has(code));
                usedCodes.add(code);
                return code;
            }

            // 🔹 Isi kode random otomatis di baris awal
            document.querySelectorAll('.int-field').forEach(field => {
                field.value = generateUniqueCode();
            });

            // 🔹 Simpan template <option> supaya bisa digunakan ulang
            const groupOptions = `@foreach ($jnpn as $g)
            <option value="{{ $g->kd_grup }}">{{ $g->penyimpangan }}</option>
        @endforeach`;

            // 🔹 Fungsi untuk inisialisasi Select2 di kolom pelaksana
            function initPelaksanaSelect2(element) {
                $(element).select2({
                    placeholder: '-- Pilih Pelaksana --',
                    allowClear: true,
                    width: 'resolve',
                    minimumInputLength: 2,
                    ajax: {
                        url: "{{ route('autocomplete.spk') }}", // route sama
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                query: params.term,
                                type: 'pegawai'
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    }
                });
            }

            // 🔹 Tambah baris baru
            addRowBtn.addEventListener('click', function() {
                const newRow = document.createElement('tr');
                const uniqueCode = generateUniqueCode(); // buat kode unik baru

                newRow.innerHTML = `
                <td hidden><input type="text" name="int[]" value="${uniqueCode}" class="form-control form-control-sm int-field"></td>
                <td>
                    <select name="kd_grup[]" class="form-control form-control-sm">
                        <option value="">-- Pilih Grup --</option>
                        ${groupOptions}
                    </select>
                </td>
                <td><textarea name="jn_penyimpangan[]" class="form-control form-control-sm"></textarea></td>
                <td><textarea name="penyebab[]" class="form-control form-control-sm"></textarea></td>
                <td><textarea name="perbaikan[]" class="form-control form-control-sm"></textarea></td>
                <td><input type="date" name="tgl_perbaikan[]" class="form-control form-control-sm"></td>
                <td><textarea name="pencegahan[]" class="form-control form-control-sm"></textarea></td>
                <td><input type="date" name="tgl_pre[]" class="form-control form-control-sm"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger removeRow">Hapus</button>
                </td>
            `;
                tableBody.appendChild(newRow);

                // Inisialisasi Select2 untuk pelaksana baru
                initPelaksanaSelect2(newRow.querySelector('.pelaksana-select'));
            });

            // 🔹 Hapus baris
            tableBody.addEventListener('click', function(e) {
                if (e.target.classList.contains('removeRow')) {
                    const row = e.target.closest('tr');
                    const code = row.querySelector('.int-field')?.value;
                    if (code) usedCodes.delete(code); // hapus dari daftar jika baris dihapus
                    row.remove();
                }
            });

            // 🔹 Inisialisasi Select2 untuk pelaksana pertama (baris default)
            initPelaksanaSelect2('select[name="pelaksana[]"]');
        });

        // Fokus otomatis ke kolom pencarian select2
        $(document).on('select2:open', () => {
            document.querySelector('.select2-container--open .select2-search__field').focus();
        });
    </script>
@endsection
