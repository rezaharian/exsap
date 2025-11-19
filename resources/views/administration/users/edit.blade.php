@extends('layouts.app')

@section('content')
    <div class="container">
        <h4 class="fw-bold mb-3">Edit User</h4>

        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="name" value="{{ $user->name }}" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ $user->email }}" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Password Baru (opsional)</label>
                <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ganti">
            </div>

            <label class="fw-bold">Role</label>
            <div class="row mb-3">
                @foreach ($roles as $role)
                    <div class="col-md-3">
                        <label>
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                {{ $user->roles->contains('name', $role->name) ? 'checked' : '' }}>
                            {{ $role->name }}
                        </label>
                    </div>
                @endforeach
            </div>

            <button class="btn btn-warning">Update</button>
        </form>
    </div>
@endsection
