@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Daftar Kerusakan Mesin</h4>
            @can('kerusakan_mesin_create')
                <a href="{{ route('teknik.kerusakanmesin.create') }}" class="btn btn-success">Tambah</a>
            @endcan
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped datatable">
            <thead class="table-light">
                <tr class="text-center">
                    <th>No</th>
                    <th>Kode Problem</th>
                    <th>Tanggal Input</th>
                    <th>Line</th>
                    <th>Unit Mesin</th>
                    <th>Masalah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prob_h as $key => $item)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-center">{{ $item->prob_cod }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($item->tgl_input)->format('d-m-Y') }}</td>
                        <td class="text-center">{{ $item->line }}</td>
                        <td>{{ $item->unitmesin }}</td>
                        <td>{{ $item->masalah }}</td>
                        <td class="text-center">
                            @can('kerusakan_mesin_edit')
                                <a href="{{ route('teknik.kerusakanmesin.edit', $item->id) }}"
                                    class="btn btn-sm btn-primary">Edit</a>
                            @endcan
                            @can('kerusakan_mesin_delete')
                                <form action="{{ route('teknik.kerusakanmesin.delete', $item->id) }}" method="POST"
                                    class="d-inline-block" onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            @endcan
                            {{-- @can('kerusakan_mesin_print')
                            <a href="{{ route('teknik.kerusakanmesin.print', $item->id) }}" class="btn btn-sm btn-success"
                                target="_blank">Print</a>
                        @endcan --}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Data tidak ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
