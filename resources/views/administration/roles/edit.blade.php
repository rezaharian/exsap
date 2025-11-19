@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="mb-3">Edit Role</h3>

        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label>Nama Role</label>
                <input type="text" name="name" value="{{ $role->name }}" class="form-control">
            </div>

            <h5>Permissions</h5>

            <div class="row">
                @foreach ($permissions as $perm)
                    <div class="col-md-3">
                        <label>
                            <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                                {{ in_array($perm->name, $rolePermissions) ? 'checked' : '' }}>
                            {{ $perm->name }}
                        </label>
                    </div>
                @endforeach
            </div>

            <button class="btn btn-primary mt-3">Update</button>
        </form>
    </div>
@endsection
