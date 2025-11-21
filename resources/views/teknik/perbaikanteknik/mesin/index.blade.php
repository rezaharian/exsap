@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm rounded-3 border-0">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center py-2">
                <h6 class="mb-0">Daftar Mesin</h6>
                <a href="{{ route('teknik.perbaikanteknik.mesin.create') }}" class="btn btn-sm btn-light">
                    + Tambah
                </a>
            </div>
            <div class="card-body p-2">

                {{-- ✅ Alert Success / Error --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show small" role="alert">
                        {{ session('success') }}

                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                        {{ session('error') }}

                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-primary text-center text-uppercase small">
                            <tr>
                                <th style="width: 15%;">Kode Mesin</th>
                                <th>Nama Mesin</th>
                                <th style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            @foreach ($data as $kode => $items)
                                @php $rowspan = count($items); @endphp
                                @foreach ($items as $i => $item)
                                    <tr>
                                        @if ($i === 0)
                                            <td class="text-center fw-bold align-middle" rowspan="{{ $rowspan }}">
                                                {{ $kode }}
                                            </td>
                                        @endif
                                        <td>{{ $item->nama_mesin }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('teknik.perbaikanteknik.mesin.edit', $item->id) }}"
                                                class="btn btn-sm btn-warning">Edit</a>

                                            <form action="{{ route('teknik.perbaikanteknik.mesin.delete', $item->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
