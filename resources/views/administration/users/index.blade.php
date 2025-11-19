@extends('layouts.app')

@section('content')
    <div class="container">
        <h4 class="fw-bold mb-3">Manajemen User</h4>

        {{-- TOMBOL TAMBAH USER --}}
        @can('user-create')
            <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">
                + Tambah User
            </a>
        @endcan

        <table class="table table-bordered datatable">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th width="180px">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $u)
                    <tr>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>
                            @foreach ($u->roles as $r)
                                <span class="badge bg-info">{{ $r->name }}</span>
                            @endforeach
                        </td>

                        <td>
                            {{-- BUTTON EDIT --}}
                            @can('user-edit')
                                <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>
                            @endcan

                            {{-- BUTTON DELETE --}}
                            @can('user-delete')
                                <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Hapus user ini?')" class="btn btn-sm btn-danger">
                                        Hapus
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
