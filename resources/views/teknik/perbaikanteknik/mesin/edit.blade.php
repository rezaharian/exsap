@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm rounded-3 border-0">
            <div class="card-header bg-warning text-dark py-2">
                <h6 class="mb-0">Edit Mesin</h6>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('teknik.perbaikanteknik.mesin.update', $item->id) }}" method="POST">
                    @csrf
                    {{-- @method('PUT') --}}

                    {{-- Pilih kode mesin --}}
                    <div class="mb-3">
                        <label for="kode_mesin_select" class="form-label small text-muted">Kode Mesin</label>

                        <select id="kode_mesin_select" class="form-control form-control-sm">
                            <option value="">-- Pilih Kode Mesin --</option>
                            @foreach ($kodeMesin as $kode)
                                <option value="{{ $kode }}"
                                    {{ old('kode_mesin', $item->kode_mesin) == $kode ? 'selected' : '' }}>
                                    {{ $kode }}
                                </option>
                            @endforeach
                            <option value="new">+ Tambah Kode Baru</option>
                        </select>

                        {{-- Input kode mesin (muncul kalau pilih new / sudah ada default value) --}}
                        <input type="text" name="kode_mesin" id="kode_mesin_input"
                            class="form-control form-control-sm mt-2 @error('kode_mesin') is-invalid @enderror"
                            placeholder="Masukkan kode mesin baru" value="{{ old('kode_mesin', $item->kode_mesin) }}">
                        @error('kode_mesin')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama Mesin --}}
                    <div class="mb-3">
                        <label for="nama_mesin" class="form-label small text-muted">Nama Mesin</label>
                        <input type="text" name="nama_mesin" id="nama_mesin"
                            class="form-control form-control-sm @error('nama_mesin') is-invalid @enderror"
                            value="{{ old('nama_mesin', $item->nama_mesin) }}" required>
                        @error('nama_mesin')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('teknik.perbaikanteknik.mesin.index') }}"
                            class="btn btn-sm btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-sm btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
    const selectKode = document.getElementById('kode_mesin_select');
    const inputKode = document.getElementById('kode_mesin_input');

    // Saat load halaman, kalau input kode sudah ada (edit), tampilkan
    if (inputKode.value) {
        inputKode.classList.remove('d-none');
        inputKode.required = true;
    }

    selectKode.addEventListener('change', function() {
        if (this.value === 'new') {
            inputKode.classList.remove('d-none');
            inputKode.required = true;
            inputKode.value = '';
        } else if (this.value) {
            inputKode.classList.remove('d-none');
            inputKode.required = true;
            inputKode.value = this.value; // isi otomatis sesuai pilihan
        } else {
            inputKode.classList.add('d-none');
            inputKode.required = false;
            inputKode.value = '';
        }
    });
</script>
