@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

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
    <div class="container ">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-md-4">
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                    Create
                </button>
            </div>
            <div class="col-md-8">
                <h4><strong><b>LIST DATA {{ strtoupper($jenis) }} </b></strong></h4>
            </div>
        </div>
        <div class="card div p-1 ">
            <table id="resultsTable" class="table table-sm table-hover ">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>SPK Nomor</th>
                        <th>Line</th>
                        <th>Shift</th>
                        <th>No Reg</th>
                        <th>Total Hasil</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datalog as $data)
                        <tr>
                            <td>{{ $data->tanggal }}</td>
                            <td>{{ $data->spk_nomor }}</td>
                            <td>{{ trim($data->line) }}</td>
                            <td>{{ $data->shift }}</td>
                            <td>{{ $data->no_reg }}</td>
                            <td>{{ $data->total_hasil }}</td>
                            <td>
                                @if (empty($data->spk_nomor) || empty($data->tanggal))
                                    <span class="text-danger">Data tidak lengkap</span>
                                @else
                                    <a href="{{ route('produksi.inputcounter.edit', [
                                        'tanggal' => $data->tanggal,
                                        'spk_nomor' => str_replace('/', '_', $data->spk_nomor),
                                        'line' => $data->line,
                                        'shift' => $data->shift,
                                        'no_reg' => $data->no_reg,
                                        'jenis' => $jenis,
                                    ]) }}"
                                        class="btn btn-warning btn-sm m-0">Edit</a>

                                    <form
                                        action="{{ route('produksi.inputcounter.delete', [
                                            'tanggal' => $data->tanggal,
                                            'spk_nomor' => str_replace('/', '_', $data->spk_nomor),
                                            'line' => $data->line,
                                            'shift' => $data->shift,
                                            'no_reg' => $data->no_reg,
                                            'jenis' => $jenis,
                                        ]) }}"
                                        method="POST" style="display:inline;"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm m-0">Delete</button>
                                    </form>
                                @endif
                            </td>



                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Create New Entry</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form untuk mengisi tanggal, spk_nomor, line, shift -->
                        <form action="{{ route('produksi.inputcounter.create') }}" method="GET">
                            <div class="row mb-3">
                                <!-- Tanggal dan Shift dalam satu baris -->
                                <div class="col-md-6">
                                    <label for="tanggal" class="form-label">Tanggal</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="shift" class="form-label">Shift</label>
                                    <select class="form-control" id="shift" name="shift" required>
                                        <option value="" disabled selected>Pilih Shift</option>
                                        <option value="I">I</option>
                                        <option value="II">II</option>
                                        <option value="III">III</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <!-- SPK Nomor dan Line dalam satu baris -->
                                <div class="col-md-6">
                                    <label for="spk_nomor" class="form-label">SPK Nomor</label>
                                    <input type="text" class="form-control" id="spk_search" name="spk_nomor"
                                        placeholder="Cari SPK Nomor" oninput="filterSPK()" />
                                    <ul id="spk_list" class="list-group mt-2"></ul> <!-- List untuk hasil pencarian -->
                                </div>
                                <div class="col-md-6">
                                    <label for="line" class="form-label">Line</label>
                                    <input type="text" class="form-control" id="line" name="line" required
                                        value="{{ $line }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <!-- No Reg -->
                                <div class="col-md-12">
                                    <label for="no_reg" class="form-label">No Reg Operator</label>
                                    <input type="text" class="form-control" name="no_reg" id="no_reg"
                                        oninput="filterNoPayroll('no_reg', 'payroll_list_operator')" required>
                                    <ul id="payroll_list_operator" class="list-group"
                                        style="display:none; position:absolute; z-index:1000;"></ul>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="leader" class="form-label">No Reg Leader</label>
                                    <input type="text" class="form-control" name="leader" id="leader"
                                        oninput="filterNoPayroll('leader', 'payroll_list_leader')" required>
                                    <ul id="payroll_list_leader" class="list-group"
                                        style="display:none; position:absolute; z-index:1000;"></ul>

                                </div>
                                <div class="col-md-6">
                                    <label for="spv" class="form-label">No Reg Supervisor </label>
                                    <input type="text" class="form-control" name="spv" id="spv"
                                        oninput="filterNoPayroll('spv', 'payroll_list_spv')" required>
                                    <ul id="payroll_list_spv" class="list-group"
                                        style="display:none; position:absolute; z-index:1000;"></ul>
                                </div>
                            </div>

                            <input hidden type="text" class="form-control" name="jenis" id="jenis"
                                value="{{ $jenis }}">

                            <button type="submit" class="btn btn-primary">Proses</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

        <script>
            const spkData = @json($spk); // Ambil data SPK dari server

            // Mengambil tanggal hari ini
            document.addEventListener("DOMContentLoaded", function() {
                var today = new Date().toISOString().split('T')[0];
                document.getElementById("tanggal").value = today;
            });

            // Fungsi untuk memfilter SPK Nomor
            function filterSPK() {
                const input = document.getElementById('spk_search').value.toLowerCase();
                const spkList = document.getElementById('spk_list');
                spkList.innerHTML = ''; // Kosongkan list hasil pencarian

                const filteredSPK = spkData.filter(item => item.SPK_NOMOR.toLowerCase().includes(input));

                if (filteredSPK.length > 0) {
                    filteredSPK.forEach(spk => {
                        const li = document.createElement('li');
                        li.classList.add('list-group-item');
                        li.textContent = spk.SPK_NOMOR;
                        li.setAttribute('data-line', spk.LINE);
                        li.addEventListener('click', function() {
                            selectSPK(spk.SPK_NOMOR, spk.LINE);
                        });
                        spkList.appendChild(li);
                    });
                } else {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = 'Tidak ada hasil';
                    spkList.appendChild(li);
                }
            }

            // Fungsi untuk memilih SPK ketika diklik
            function selectSPK(spkNomor, line) {
                document.getElementById('spk_search').value = spkNomor;
                // document.getElementById('line').value = line;
                document.getElementById('spk_list').innerHTML = ''; // Kosongkan list hasil pencarian setelah memilih
            }

            // Fungsi untuk memilih SPK saat menekan Enter
            document.getElementById('spk_search').addEventListener('keydown', function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    const spkList = document.getElementById('spk_list');
                    const firstSPK = spkList.querySelector('li');

                    if (firstSPK && firstSPK.getAttribute('data-line')) {
                        selectSPK(firstSPK.textContent, firstSPK.getAttribute('data-line'));
                    }
                }
            });

            const noregData = @json($noreg); // Ambil data No. Payroll dan Nama dari server

            // Fungsi untuk memfilter No. Payroll dan Nama
            function filterNoPayroll(inputId, listId) {
                const input = document.getElementById(inputId).value.toLowerCase();
                const payrollList = document.getElementById(listId);
                payrollList.innerHTML = '';
                payrollList.style.display = 'none';

                const filteredPayroll = noregData.filter(item =>
                    item.no_payroll.toLowerCase().includes(input) ||
                    item.nama_asli.toLowerCase().includes(input)
                );

                if (filteredPayroll.length > 0) {
                    filteredPayroll.forEach(reg => {
                        const li = document.createElement('li');
                        li.classList.add('list-group-item');
                        li.textContent = `${reg.no_payroll} - ${reg.nama_asli}`;
                        li.setAttribute('data-no-payroll', reg.no_payroll);
                        li.addEventListener('click', function() {
                            selectNoPayroll(inputId, listId, reg.no_payroll);
                        });
                        payrollList.appendChild(li);
                    });
                    payrollList.style.display = 'block';
                } else {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = 'Tidak ada hasil';
                    payrollList.appendChild(li);
                    payrollList.style.display = 'block';
                }
            }

            // Fungsi untuk memilih No. Payroll ketika diklik
            function selectNoPayroll(inputId, listId, noPayroll) {
                document.getElementById(inputId).value = noPayroll;
                const list = document.getElementById(listId);
                list.innerHTML = '';
                list.style.display = 'none';
            }

            // Menyembunyikan daftar jika klik di luar
            document.addEventListener('click', function(event) {
                const payrollList = document.getElementById('payroll_list');
                const input = document.getElementById('no_reg');

                if (!payrollList.contains(event.target) && event.target !== input) {
                    payrollList.style.display = 'none'; // Sembunyikan daftar jika klik di luar
                }
            });

            $(document).ready(function() {
                $('#resultsTable').DataTable({
                    "order": [
                        [0, "desc"], // Urutkan kolom pertama (index 0) secara descending
                        [3, "desc"] // Urutkan kolom ketiga (index 2) secara ascending
                    ]
                });
            });
        </script>
    @endsection
