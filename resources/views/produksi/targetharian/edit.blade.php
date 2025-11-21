@extends('layouts.app')

@section('content')
    <div class="container card pt-2">

        <!-- Menampilkan pesan sukses -->
        @if (session('success'))
            <div class="alert
        alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Menampilkan pesan error -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif

        <h3 class="text-center mb-4"><b>EDIT DATA TARGET PRODUKSI TANGGAL <strong class="text-primary"><u>
                        {{ \Carbon\Carbon::parse($tgl)->translatedFormat('d F Y') }}</u></strong></b></h3>


        <form action="{{ route('produksi.targetharian.update', ['tgl_prod' => $tgl]) }}" method="POST">
            @csrf
            @method('PUT') <!-- Pastikan ini ada untuk mengoverride metode menjadi PUT -->

            <div class="mb-4" hidden>
                <label for="tgl_prod" class="form-label">Tanggal Produksi</label>
                <input type="date" class="form-control" id="tgl_prod" value="{{ $tgl }}" name="tgl_prod"
                    readonly required>
            </div>

            <table class="table table-sm table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Line</th>
                        <th>Target</th>
                    </tr>
                </thead>
                <tbody id="target-table-body">
                    @foreach ($data as $item)
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="line[]" value="{{ $item->line }}"
                                    required readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control target-input" name="target[]"
                                    value="{{ $item->target }}" oninput="calculateTotal()">
                            </td>
                        </tr>
                    @endforeach
                    <tr class="table-primary">
                        <th><b>Total Target</b></th>
                        <th>
                            <h3 class="text-primary"><b><span id="total-target">0</span></b></h3>
                        </th>
                    </tr>
                </tbody>
            </table>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function calculateTotal() {
            const inputs = document.querySelectorAll('.target-input');
            let total = 0;
            inputs.forEach(input => {
                const value = parseFloat(input.value) || 0; // Ambil nilai dan pastikan menjadi angka
                total += value;
            });
            document.getElementById('total-target').innerText = total; // Update total di tampilan
        }

        // Panggil calculateTotal pada awal untuk menghitung total yang sudah ada
        document.addEventListener('DOMContentLoaded', calculateTotal);
    </script>
@endsection
