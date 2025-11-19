@extends('layouts.app')

@section('content')
    <div class="container">
        <h4 class="fw-bold mb-3">Tambah User Baru</h4>

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <label class="fw-bold">Role</label>
            <div class="row mb-3">
                @foreach ($roles as $role)
                    <div class="col-md-3">
                        <label>
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}">
                            {{ $role->name }}
                        </label>
                    </div>
                @endforeach
            </div>

            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
