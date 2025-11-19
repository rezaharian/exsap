@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="mb-3">Tambah Role</h3>

        <form action="{{ route('roles.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Nama Role</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <h5>Permissions:</h5>

            <div class="row">
                @foreach ($permissions as $perm)
                    <div class="col-md-3">
                        <label>
                            <input type="checkbox" name="permissions[]" value="{{ $perm->name }}">
                            {{ $perm->name }}
                        </label>
                    </div>
                @endforeach
            </div>

            <button class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>
@endsection
