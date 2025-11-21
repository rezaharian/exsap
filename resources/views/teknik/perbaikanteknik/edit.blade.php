@extends('layouts.app')


@section('content')
    <div class="container mt-1">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-light">
                <h6 class="mb-0 text-secondary">Edit Data Kerusakan</h6>
            </div>
            <div class="card-body bg-white">
                <form action="{{ route('teknik.perbaikanteknik.mesin.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')



                    {{-- Data utama --}}
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Tanggal</label>
                            <input type="date" name="tgl" class="form-control form-control-sm"
                                value="{{ \Carbon\Carbon::parse($data->tgl)->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Lokasi Line</label>
                            <select name="lokasi_line" class="form-control form-control-sm" required>
                                <option value="">-- Pilih Line --</option>
                                @foreach ($line as $ln)
                                    <option value="{{ $ln->LINE }}"
                                        {{ old('lokasi_line', $data->lokasi_line) == $ln->LINE ? 'selected' : '' }}>
                                        {{ $ln->LINE }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Kode Mesin</label>
                            <select name="no_mesin" id="kode_mesin" class="form-control form-control-sm">
                                <option value="">-- Pilih No Mesin --</option>
                                @foreach ($kodeMesin as $kode)
                                    <option value="{{ $kode }}"
                                        {{ old('no_mesin', $data->no_mesin) == $kode ? 'selected' : '' }}>
                                        {{ $kode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Nama Mesin</label>
                            <select name="nama_mesin" id="nama_mesin" class="form-control form-control-sm">
                                <option value="">-- Pilih Mesin --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted d-block">Klasifikasi</label>
                            @php $klasifikasi = old('klasifikasi', $data->klasifikasi); @endphp
                            @foreach (['JADWAL PEMELIHARAAN MESIN (JPRM)', 'MESIN / ALAT RUSAK', 'GANTI MODEL', "SMALL STOP > 5' < 15'", 'PERBAIKAN / REKONDISI KOMPONEN', 'JADWAL PERBAIKAN MESIN (JPM)'] as $i => $val)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="klasifikasi"
                                        id="klas{{ $i }}" value="{{ $val }}"
                                        {{ $klasifikasi == $val ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="klas{{ $i }}">{{ $val }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Sparepart --}}
                    <div class="mb-3">
                        <label class="form-label small text-muted">Sparepart</label>
                        <table class="table table-sm table-bordered" id="sparepartTable">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th width="5%">#</th>
                                    <th width="45%">Nama Sparepart</th>
                                    <th width="20%">Jumlah</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($spareparts->isNotEmpty())
                                    @foreach ($spareparts as $index => $sp)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <input type="text" name="sparepart_nama[]"
                                                    class="form-control form-control-sm" value="{{ $sp->nama_sparepart }}"
                                                    required>
                                                <input type="hidden" name="sparepart_id[]" value="{{ $sp->id }}">
                                            </td>
                                            <td>
                                                <input type="number" name="sparepart_jumlah[]"
                                                    class="form-control form-control-sm" value="{{ $sp->jumlah }}"
                                                    required>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-danger removeRow">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td><input type="text" name="sparepart_nama[]"
                                                class="form-control form-control-sm" required></td>
                                        <td><input type="number" name="sparepart_jumlah[]"
                                                class="form-control form-control-sm" required></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger removeRow">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-sm btn-success" id="addSparepart">
                            <i class="fas fa-plus"></i> Tambah Sparepart
                        </button>
                    </div>

                    {{-- Lain-lain --}}
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Waktu Mulai</label>
                            <input type="time" name="waktu_mulai" class="form-control form-control-sm"
                                value="{{ \Carbon\Carbon::parse($data->waktu_mulai)->format('H:i') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Waktu Selesai</label>
                            <input type="time" name="waktu_selesai" class="form-control form-control-sm"
                                value="{{ \Carbon\Carbon::parse($data->waktu_selesai)->format('H:i') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Durasi (Jam)</label>
                            <input type="number" step="0.1" name="durasi_jam" class="form-control form-control-sm"
                                value="{{ old('durasi_jam', $data->durasi_jam) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Pelaksana <span
                                    class="text-danger">*</span></label>
                            <textarea name="pelaksana" class="form-control form-control-sm" rows="2" required>{{ old('pelaksana', $data->pelaksana) }}</textarea>
                        </div>

                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Deskripsi Masalah <span
                                    class="text-danger">*</span></label>
                            <textarea name="deskripsi_masalah" class="form-control form-control-sm" rows="2" required>{{ old('deskripsi_masalah', $data->deskripsi_masalah) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Penyebab</label>
                            <textarea name="keterangan" class="form-control form-control-sm" rows="2">{{ old('keterangan', $data->keterangan) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Tindakan Perbaikan</label>
                            <textarea name="tindakan_perbaikan" class="form-control form-control-sm" rows="2">{{ old('tindakan_perbaikan', $data->tindakan_perbaikan) }}</textarea>
                        </div>

                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary me-2 btn-sm px-3">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('teknik.perbaikanteknik.index') }}"
                            class="btn btn-outline-secondary btn-sm px-3">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dynamic sparepart row
            let sparepartTable = document.querySelector('#sparepartTable tbody');
            let addBtn = document.getElementById('addSparepart');

            addBtn.addEventListener('click', function() {
                let rowCount = sparepartTable.rows.length + 1;
                let row = `
                    <tr>
                        <td class="text-center">${rowCount}</td>
                        <td><input type="text" name="sparepart_nama[]" class="form-control form-control-sm" required></td>
                        <td><input type="number" name="sparepart_jumlah[]" class="form-control form-control-sm" required></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger removeRow"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>`;
                sparepartTable.insertAdjacentHTML('beforeend', row);
                renumberRows();
            });

            // remove row
            sparepartTable.addEventListener('click', function(e) {
                if (e.target.closest('.removeRow')) {
                    e.target.closest('tr').remove();
                    renumberRows();
                }
            });

            function renumberRows() {
                [...sparepartTable.rows].forEach((row, i) => {
                    row.cells[0].textContent = i + 1;
                });
            }

            // Mesin select logic
            let kodeMesinSelect = document.getElementById('kode_mesin');
            let namaMesinSelect = document.getElementById('nama_mesin');
            let selectedNamaMesin = "{{ old('nama_mesin', $data->nama_mesin) }}";

            function loadNamaMesin(kode, selected = null) {
                namaMesinSelect.innerHTML = '<option value="">-- Pilih Mesin --</option>';
                if (kode) {
                    fetch(`/teknik/perbaikanteknik/namamesin/get/${kode}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(nama => {
                                let option = document.createElement('option');
                                option.value = nama;
                                option.textContent = nama;
                                if (selected && selected === nama) {
                                    option.selected = true;
                                }
                                namaMesinSelect.appendChild(option);
                            });
                        });
                }
            }

            if (kodeMesinSelect.value) {
                loadNamaMesin(kodeMesinSelect.value, selectedNamaMesin);
            }

            kodeMesinSelect.addEventListener('change', function() {
                loadNamaMesin(this.value);
            });
        });
    </script>
@endsection
