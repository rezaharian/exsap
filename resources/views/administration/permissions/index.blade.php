@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Permissions</h1>
        <a href="{{ route('mgpermissions.create') }}" class="btn btn-primary mb-3">Add Permission</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover datatable">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name permission</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $permission->name }}</td>
                        <td>
                            <a href="{{ route('mgpermissions.edit', ['mgpermission' => $permission->id]) }}"
                                class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('mgpermissions.destroy', ['mgpermission' => $permission->id]) }}"
                                method="POST" class="d-inline" onsubmit="return confirm('Delete this permission?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
