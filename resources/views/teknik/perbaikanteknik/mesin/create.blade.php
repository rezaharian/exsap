@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm rounded-3 border-0">
            <div class="card-header bg-primary text-white py-2">
                <h6 class="mb-0">Tambah Mesin</h6>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('teknik.perbaikanteknik.mesin.store') }}" method="POST">
                    @csrf

                    {{-- Pilih kode mesin --}}
                    <div class="mb-3">
                        <label for="kode_mesin_select" class="form-label small text-muted">Kode Mesin</label>

                        <select id="kode_mesin_select" class="form-control form-control-sm form-select form-select-sm">
                            <option value="">-- Pilih Kode Mesin --</option>
                            @foreach ($kodeMesin as $kode)
                                <option value="{{ $kode }}">{{ $kode }}</option>
                            @endforeach
                            <option value="new">+ Tambah Kode Baru</option>
                        </select>

                        {{-- Input kode mesin baru (muncul hanya jika pilih "new") --}}
                        <input type="text" name="kode_mesin" id="kode_mesin_input"
                            class="form-control form-control-sm mt-2 d-none @error('kode_mesin') is-invalid @enderror"
                            placeholder="Masukkan kode mesin baru" value="{{ old('kode_mesin') }}">
                        @error('kode_mesin')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama Mesin --}}
                    <div class="mb-3">
                        <label for="nama_mesin" class="form-label small text-muted">Nama Mesin</label>
                        <input type="text" name="nama_mesin" id="nama_mesin"
                            class="form-control form-control-sm @error('nama_mesin') is-invalid @enderror"
                            value="{{ old('nama_mesin') }}" required>
                        @error('nama_mesin')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tombol --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('teknik.perbaikanteknik.mesin.index') }}"
                            class="btn btn-sm btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const selectKode = document.getElementById('kode_mesin_select');
        const inputKode = document.getElementById('kode_mesin_input');

        selectKode.addEventListener('change', function() {
            if (this.value === 'new') {
                inputKode.classList.remove('d-none');
                inputKode.required = true;
                inputKode.value = '';
            } else if (this.value) {
                inputKode.classList.remove('d-none');
                inputKode.required = true;
                inputKode.value = this.value; // otomatis isi kode yg dipilih
            } else {
                inputKode.classList.add('d-none');
                inputKode.required = false;
                inputKode.value = '';
            }
        });
    </script>
@endsection
