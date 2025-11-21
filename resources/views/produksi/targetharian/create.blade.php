@extends('layouts.app')

@section('content')
    <div class="container card p-4 border-primary mt-3">
        <h2 class="text-center mb-2"><b>INPUT TARGET PRODUKSI</b></h2>

        <form action="{{ route('produksi.targetharian.store') }}" method="POST">
            @csrf
            <input type="hidden" name="tanggal" value="{{ $tgl }}">

            <div class="mb-4">
                <label for="tgl_prod" class="form-label">Tanggal Produksi</label>
                <input type="date" class="form-control" id="tgl_prod" value="{{ $tgl }}" name="tgl_prod"
                    readonly required>
            </div>

            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Line</th>
                        <th>Target</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lines as $index => $line)
                        <tr>
                            <td><input type="text" class="form-control" name="lines[{{ $index }}][line]"
                                    value="{{ $line->LINE }}" readonly></td>
                            <td><input type="number" class="form-control" name="lines[{{ $index }}][target]"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
