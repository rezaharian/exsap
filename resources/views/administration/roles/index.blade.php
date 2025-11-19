@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="mb-3">Daftar Role</h3>

        {{-- TOMBOL TAMBAH ROLE (Hanya user yang punya izin role-create) --}}
        @can('create roles')
            <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">
                <i class="bi bi-plus-circle"></i> Tambah Role
            </a>
        @endcan

        <table class="table table-bordered datatable">
            <thead>
                <tr>
                    <th>Nama Role</th>
                    <th>Permissions</th>
                    <th width="150px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>

                        <td>
                            @forelse ($role->permissions as $perm)
                                <span class="badge bg-info text-dark">{{ $perm->name }}</span>
                            @empty
                                <span class="text-muted">Tidak ada</span>
                            @endforelse
                        </td>

                        <td>
                            {{-- BUTTON EDIT --}}
                            @can('edit roles')
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i>
                                    Edit
                                </a>
                            @endcan

                            {{-- BUTTON DELETE --}}
                            @can('delete roles')
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus role ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Del
                                    </button>
                                </form>
                            @endcan
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
