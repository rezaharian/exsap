@extends('layouts.app')

@section('content')
    <style>
        .list-group-item:hover {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        select:hover {
            border-color: #007bff;
        }

        #resultsTable {
            font-size: 14px;
            width: 100%;
        }

        #resultsTable td,
        #resultsTable th {
            padding: 2px 2px;
        }

        /* Sembunyikan tabel Rp BB Terakhir tanpa atribut hidden */
        .hidden-cell {
            display: none !important;
        }
    </style>

    <div class="container card p-4 border-primary mt-3">
        <h5 class="text-center mb-2"><b>INPUT SISA STOK BAHAN BAKU</b></h5>

        <form action="{{ route('produksi.sisastokbahanbaku.store') }}" method="POST">
            @csrf
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">

            <div class="mb-4">
                <label for="tanggal" class="form-label">Tanggal :</label>
                <input type="date" class="form-control form-control-sm" id="tanggal" value="{{ $tanggal }}"
                    name="tanggal" readonly required>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover" id="stok-table">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>SPK Nomor</th>
                            <th>Sisa Bahan Baku</th>
                            <th>Rp Bahan Baku</th>
                            <th class="hidden-cell">Rp Bahan Baku Terakhir</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="stok-table-body">
                        <!-- Baris pertama (default) -->
                        <tr>
                            <td>1</td>
                            <td>
                                <input type="text" class="form-control form-control-sm spk_search" name="spk_nomor[]"
                                    placeholder="Cari SPK Nomor">
                                <ul class="spk_list list-group mt-2"></ul>
                            </td>
                            <td><input type="number" class="form-control form-control-sm sisa_bb_input" name="sisa_bb[]"
                                    required></td>
                            <td><input type="text" class="form-control form-control-sm rp_bb_input" name="rp_bb[]"
                                    required></td>

                            {{-- HIDDEN INPUT TIDAK REQUIRED --}}
                            <td class="hidden-cell">
                                <input type="number" class="form-control form-control-sm rp_bbl_input" name="rp_bbl[]">
                            </td>

                            <td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <div class="text-center mt-4">
                <button type="button" class="btn btn-secondary" id="add-row">Tambah Baris</button>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowIndex = 1;
            const spkData = @json($spk);

            function addRow() {
                rowIndex++;
                const row = document.createElement('tr');

                row.innerHTML = `
                <td>${rowIndex}</td>
                <td>
                    <input type="text" class="form-control form-control-sm spk_search" name="spk_nomor[]" placeholder="Cari SPK Nomor" />
                    <ul class="spk_list list-group mt-2"></ul>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm sisa_bb_input" name="sisa_bb[]" required>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm rp_bb_input" name="rp_bb[]" required>
                </td>
                <td class="hidden-cell">
                    <input type="number" class="form-control form-control-sm rp_bbl_input" name="rp_bbl[]">
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-row">Hapus</button>
                </td>
                `;

                document.getElementById('stok-table-body').appendChild(row);
                attachEventsToRow(row);
            }

            function attachEventsToRow(row) {
                const spkInput = row.querySelector('.spk_search');
                const sisaInput = row.querySelector('.sisa_bb_input');
                const rpBblInput = row.querySelector('.rp_bbl_input');
                const rpBbInput = row.querySelector('.rp_bb_input');
                const removeBtn = row.querySelector('.remove-row');

                spkInput.addEventListener('input', filterSPK);

                sisaInput.addEventListener('input', function() {
                    calculateRowTotal(sisaInput, rpBblInput, rpBbInput);
                });

                rpBblInput.addEventListener('input', function() {
                    calculateRowTotal(sisaInput, rpBblInput, rpBbInput);
                });

                removeBtn.addEventListener('click', () => {
                    row.remove();
                });
            }

            function calculateRowTotal(sisaInput, bblInput, bbInput) {
                const sisa = parseFloat((sisaInput.value || '0'));
                const bbl = parseFloat((bblInput.value || '0'));
                bbInput.value = (sisa * bbl).toFixed(2);
            }

            function filterSPK(event) {
                const input = event.target.value.toLowerCase();
                const spkList = event.target.nextElementSibling;
                spkList.innerHTML = '';

                const filtered = spkData.filter(item => item.SPK_NOMOR.toLowerCase().includes(input));

                if (filtered.length) {
                    filtered.forEach(spk => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item';
                        li.textContent = spk.SPK_NOMOR;
                        li.onclick = () => selectSPK(spk.SPK_NOMOR, spk.LINE, event.target);
                        spkList.appendChild(li);
                    });
                } else {
                    spkList.innerHTML = '<li class="list-group-item">Tidak ada hasil</li>';
                }
            }

            function selectSPK(spkNomor, line, inputElement) {
                inputElement.value = spkNomor;
                inputElement.nextElementSibling.innerHTML = '';

                const tanggal = document.getElementById('tanggal').value;
                const trElement = inputElement.closest('tr');

                fetch("{{ route('produksi.sisastokbahanbaku.spkfromview') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            spk_nomor: spkNomor,
                            tanggal: tanggal
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        const rpBblInput = trElement.querySelector('.rp_bbl_input');
                        const sisaInput = trElement.querySelector('.sisa_bb_input');
                        const rpBbInput = trElement.querySelector('.rp_bb_input');

                        if (data.data) {
                            rpBblInput.value = data.data.OUTGDS_PRC;
                            calculateRowTotal(sisaInput, rpBblInput, rpBbInput);
                        } else {
                            rpBblInput.value = '';
                            rpBbInput.value = '';
                        }
                    })
                    .catch(error => console.error('Terjadi kesalahan:', error));
            }

            document.getElementById('add-row').addEventListener('click', addRow);
            document.querySelectorAll('#stok-table-body tr').forEach(attachEventsToRow);
        });
    </script>
@endsection
