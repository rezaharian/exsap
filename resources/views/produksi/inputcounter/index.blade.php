@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Pilih Counter</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('produksi.inputcounter.list') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="jenis">Jenis</label>
                        <select class="form-control" id="jenis" name="jenis" required>
                            <option value="" disabled selected>Select Jenis</option>
                            <option value="Extruder">Extruder</option>
                            <option value="Printing">Printing</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="line">Line</label>
                        <select class="form-control" id="line" name="line" required>
                            <option value="" disabled selected>Select Line</option>
                            @foreach ($line as $item)
                                <option value="{{ $item->LINE }}">{{ $item->LINE }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection


{{-- @extends('layouts.app') <!-- Sesuaikan layout jika diperlukan -->

@section('content')
    <div class="container" style="font-size: 9pt;">
        <h2>Data Pengukuran dan Log</h2>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Shift</th>
                    <th>Jam</th>
                    <th>Produksi</th>
                    <th>Pengukuran Asli</th>
                    <th>Pengukuran & Log (Combined)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item['shift'] }}</td>
                        <td>{{ $item['jam'] }}</td>
                        <td>{{ $item['produksi'] }}</td>

                        <!-- Tampilkan Pengukuran Asli -->
                        <td>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Pengukuran</th>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item['pengukuran'] as $pengukuran)
                                        <tr>
                                            <td>{{ key($pengukuran) }}</td> <!-- Nama Pengukuran -->
                                            <td>{{ current($pengukuran) ?? '-' }}</td> <!-- Nilai Pengukuran -->
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>

                        <!-- Tampilkan Combined Pengukuran dan Log -->
                        <td>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Pengukuran</th>
                                        <th>Log Nama</th>
                                        <th>Tipe</th>
                                        <th>Standar</th>
                                        <th>Toleransi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($item['combined'] as $combined)
                                        <tr>
                                            <td>{{ key($combined['pengukuran']) }}</td> <!-- Nama pengukuran -->
                                            <td>{{ $combined['log']['nama'] ?? '-' }}</td> <!-- Nama log -->
                                            <td>{{ $combined['log']['tipe'] ?? '-' }}</td> <!-- Tipe log -->
                                            <td>{{ $combined['log']['standar'] ?? '-' }}</td> <!-- Standar log -->
                                            <td>{{ $combined['log']['toleransi'] ?? '-' }}</td> <!-- Toleransi log -->
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection --}}
