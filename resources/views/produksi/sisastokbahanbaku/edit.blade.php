@extends('layouts.app')

@section('content')
    <style>
        /* Mengatur tampilan saat hover di dalam list-group-item */
        .list-group-item:hover {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        /* Mengatur tampilan select saat hover */
        select:hover {
            border-color: #007bff;
        }

        /* Atur ukuran tabel */
        #resultsTable {
            font-size: 14px;
            /* Atur ukuran font lebih kecil */
            width: 100%;
            /* Atur lebar tabel sesuai kebutuhan */
        }

        /* Atur padding sel tabel agar lebih kecil */
        #resultsTable td,
        #resultsTable th {
            padding: 2px 2px;
            /* Atur padding lebih kecil */
        }
    </style>

    <div class="container">
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <h5 class="text-center mb-4">
            <b>EDIT DATA SISA STOK BAHAN BAKU TANGGAL <strong class="text-primary"><u>
                        {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</u></strong></b>
        </h5>
        <input type="hidden" name="tanggal" id="tanggal" value="{{ $tanggal }}">



        <!-- Form untuk mengupdate data -->
        <form action="{{ route('produksi.sisastokbahanbaku.update', ['tanggal' => $tanggal]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>SPK Nomor</th>
                            <th hidden>Tanggal</th>
                            <th>Sisa Bahan Baku</th>
                            <th>Rp Bahan Baku</th>
                            <th hidden>Rp Bahan Baku Sebelumnya</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="stok-table-body">
                        @foreach ($data as $index => $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <input type="text" class="form-control form-control-sm  spk_search"
                                        name="spk_nomor[]" id="spk_search_{{ $index }}"
                                        value="{{ $item['spk_nomor'] }} " placeholder="Cari SPK Nomor"
                                        oninput="filterSPK({{ $index }})" />
                                    <ul id="spk_list_{{ $index }}" class="list-group mt-2"></ul>
                                </td>
                                <td hidden>
                                    <input type="date" class="form-control form-control-sm" name="tanggal[]"
                                        value="{{ $item['tanggal'] }}" required readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm sisa-bb-input"
                                        name="sisa_bb[]" id="sisa_bb_{{ $index }}"
                                        value="{{ $item['sisa_bb'] !== null ? rtrim(rtrim(number_format($item['sisa_bb'], 1, '.', ''), '0'), '.') : '' }}"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '')"
                                        onblur="if(this.value === '.' || this.value === '') this.value = ''" />
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm rp_bb-input" name="rp_bb[]"
                                        value="{{ $item['rp_bb'] }}" id="rp_bb_{{ $index }}">
                                </td>
                                <td hidden>
                                    <input type="text" class="form-control form-control-sm rp_bbr-input" name="rp_bbr[]"
                                        id="rp_bb_r{{ $index }}">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            <!-- Tombol Tambah Baris -->
            <div class="text-center mt-4">
                <button type="button" class="btn btn-success" id="add-row">Tambah Baris</button>
            </div>

            <!-- Tombol Submit -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowIndex = {{ count($data) }};
            const spkData = @json($spk);

            function attachEvents(context = document) {
                context.querySelectorAll('.spk_search').forEach(input => {
                    input.removeEventListener('input', handleInput);
                    input.removeEventListener('keydown', handleKeyDown);
                    input.addEventListener('input', handleInput);
                    input.addEventListener('keydown', handleKeyDown);
                });

                context.querySelectorAll('.sisa-bb-input').forEach(input => {
                    input.removeEventListener('input', calculateRow);
                    input.addEventListener('input', calculateRow);
                });

                context.querySelectorAll('.rp_bbr-input').forEach(input => {
                    input.removeEventListener('input', calculateRow);
                    input.addEventListener('input', calculateRow);
                });

                context.querySelectorAll('.remove-row').forEach(button => {
                    button.removeEventListener('click', removeRow);
                    button.addEventListener('click', removeRow);
                });
            }

            function addRow() {
                rowIndex++;
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                <td>${rowIndex}</td>
                <td>
                    <input type="text" class="form-control form-control-sm spk_search" name="spk_nomor[]" placeholder="Cari SPK Nomor" />
                    <ul class="spk_list list-group mt-2"></ul>
                </td>
                <td hidden>
                    <input type="date" class="form-control form-control-sm" name="tanggal[]" value="{{ $tanggal }}" readonly>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm sisa-bb-input" name="sisa_bb[]" step="0.1" lang="en"
                        oninput="this.value = this.value.replace(/[^0-9.]/g, '')"
                        onblur="if(this.value === '.' || this.value === '') this.value = ''" />
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm rp_bb-input" name="rp_bb[]" />
                </td>
                <td hidden>
                    <input type="text" class="form-control form-control-sm rp_bbr-input" name="rp_bbr[]" />
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>
                </td>
            `;
                document.getElementById('stok-table-body').appendChild(newRow);
                attachEvents(newRow);
            }

            function handleInput(e) {
                const input = e.target;
                const value = input.value.toLowerCase();
                const list = input.nextElementSibling;
                list.innerHTML = '';

                if (!value) return;

                const filtered = spkData.filter(item => item.SPK_NOMOR.toLowerCase().includes(value));

                if (filtered.length > 0) {
                    filtered.forEach(item => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item';
                        li.textContent = item.SPK_NOMOR;
                        li.setAttribute('data-line', item.LINE);
                        li.addEventListener('click', () => {
                            input.value = item.SPK_NOMOR;
                            list.innerHTML = '';
                            fetchSPKDetails(item.SPK_NOMOR, input);
                        });
                        list.appendChild(li);
                    });
                } else {
                    const li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.textContent = 'Tidak ada hasil';
                    list.appendChild(li);
                }
            }

            function handleKeyDown(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const list = e.target.nextElementSibling;
                    const first = list.querySelector('li');
                    if (first && first.getAttribute('data-line')) {
                        e.target.value = first.textContent;
                        list.innerHTML = '';
                        fetchSPKDetails(first.textContent, e.target);
                    }
                }
            }

            function fetchSPKDetails(spk_nomor, inputElement) {
                const tr = inputElement.closest('tr');
                const tanggal = tr.querySelector('input[name="tanggal[]"]').value;
                const rp_bb_input = tr.querySelector('.rp_bb-input');
                const rp_bbr_input = tr.querySelector('.rp_bbr-input');

                fetch("{{ route('produksi.sisastokbahanbaku.spkfromview') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            spk_nomor,
                            tanggal
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.data) {
                            const harga = parseFloat(data.data.OUTGDS_PRC || 0).toFixed(2);
                            rp_bb_input.value = harga;
                            rp_bbr_input.value = harga;
                            calculateRowByElement(rp_bb_input);
                        } else {
                            rp_bb_input.value = '';
                            rp_bbr_input.value = '';
                            calculateRowByElement(rp_bb_input);
                        }
                    })
                    .catch(err => console.error('Gagal mengambil data:', err));
            }

            function calculateRow(e) {
                const row = e.target.closest('tr');
                const sisa = parseFloat(row.querySelector('.sisa-bb-input')?.value || 0);
                const harga = parseFloat(row.querySelector('.rp_bbr-input')?.value || 0);
                const hasil = (sisa * harga).toFixed(2);
                const output = row.querySelector('.rp_bb-input');
                if (output) output.value = hasil;
            }

            function calculateRowByElement(element) {
                const row = element.closest('tr');
                const sisa = parseFloat(row.querySelector('.sisa-bb-input')?.value || 0);
                const harga = parseFloat(row.querySelector('.rp_bbr-input')?.value || 0);
                const hasil = (sisa * harga).toFixed(2);
                const output = row.querySelector('.rp_bb-input');
                if (output) output.value = hasil;
            }

            function removeRow(e) {
                const tr = e.target.closest('tr');
                tr.remove();
            }

            document.getElementById('add-row').addEventListener('click', function() {
                addRow();
            });

            attachEvents();

        });
    </script>
@endsection
