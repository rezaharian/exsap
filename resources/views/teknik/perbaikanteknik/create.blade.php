@extends('layouts.app')

@section('content')
    <div class="container mt-1">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-light">
                <h6 class="mb-0 text-secondary">Tambah Data Kerusakan</h6>
            </div>
            <div class="card-body bg-white">
                <form action="{{ route('teknik.perbaikanteknik.store') }}" method="POST">
                    @csrf

                    <!-- Row 1: Tanggal, Lokasi Line, Kode Mesin, Nama Mesin, Klasifikasi -->
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Tanggal</label>
                            <input type="date" name="tgl" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Lokasi Line</label>
                            <select name="lokasi_line" class="form-control form-control-sm">
                                <option value="">-- Pilih Line --</option>
                                @foreach ($line as $l)
                                    <option value="{{ $l->LINE }}">{{ $l->LINE }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Kode Mesin</label>
                            <select name="no_mesin" id="kode_mesin" class="form-control form-control-sm">
                                <option value="">-- Pilih No Mesin --</option>
                                @foreach ($kodeMesin as $kode)
                                    <option value="{{ $kode }}">{{ $kode }}</option>
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
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="klasifikasi" id="klas1"
                                    value="JADWAL PEMELIHARAAN MESIN (JPRM)">
                                <label class="form-check-label" for="klas1">Jadwal Pemeliharaan Mesin (JPRM)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="klasifikasi" id="klas2"
                                    value="MESIN / ALAT RUSAK">
                                <label class="form-check-label" for="klas2">Mesin / Alat Rusak</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="klasifikasi" id="klas3"
                                    value="GANTI MODEL">
                                <label class="form-check-label" for="klas3">Ganti Model</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="klasifikasi" id="klas4"
                                    value="SMALL STOP > 5' < 15'">
                                <label class="form-check-label" for="klas4">Small Stop &gt; 5' &lt; 15'</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="klasifikasi" id="klas5"
                                    value="PERBAIKAN / REKONDISI KOMPONEN">
                                <label class="form-check-label" for="klas5">Perbaikan / Rekondisi Komponen</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="klasifikasi" id="klas6"
                                    value="JADWAL PERBAIKAN MESIN (JPM)">
                                <label class="form-check-label" for="klas6">Jadwal Perbaikan Mesin (JPM)</label>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Material / Sparepart + Jumlah Part Dinamis -->
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label class="form-label small text-muted">Material / Sparepart & Jumlah Part</label>
                            <table class="table table-sm table-bordered" id="materialTable">
                                <thead>
                                    <tr>
                                        <th style="width: 60%">Material / Sparepart</th>
                                        <th style="width: 20%">Jumlah Part</th>
                                        <th style="width: 20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" name="material_sparepart[]"
                                                class="form-control form-control-sm"></td>
                                        <td><input type="number" name="jumlah[]" class="form-control form-control-sm"></td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm addRow">Tambah</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Row 3: Pelaksana -->
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Pelaksana</label>
                            <textarea name="pelaksana" class="form-control form-control-sm" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Deskripsi Masalah <span
                                    class="text-danger">*</span></label>
                            <textarea name="deskripsi_masalah" class="form-control form-control-sm" rows="2" required></textarea>
                        </div>
                    </div>

                    <!-- Row 4: Waktu -->
                    <div class="row mb-2">
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Waktu Mulai</label>
                            <input type="time" name="waktu_mulai" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Waktu Selesai</label>
                            <input type="time" name="waktu_selesai" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Durasi (Jam)</label>
                            <input type="number" step="0.01" name="durasi_jam" class="form-control form-control-sm">
                        </div>
                    </div>

                    <!-- Row 5: Tindakan dan Keterangan -->
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Penyebab</label>
                            <textarea name="keterangan" class="form-control form-control-sm" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Tindakan Perbaikan</label>
                            <textarea name="tindakan_perbaikan" class="form-control form-control-sm" rows="2"></textarea>
                        </div>

                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success me-3 btn-sm px-3">
                            <i class="bi bi-check-circle"></i> Simpan
                        </button>
                        <a href="{{ route('teknik.perbaikanteknik.index') }}"
                            class="btn btn-outline-secondary ms-2 btn-sm px-3">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .form-label {
            font-weight: 500;
        }

        textarea,
        input {
            background-color: #fdfdfd !important;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch Nama Mesin sesuai kode
            document.getElementById('kode_mesin').addEventListener('change', function() {
                let kode = this.value;
                let namaMesinSelect = document.getElementById('nama_mesin');
                namaMesinSelect.innerHTML = '<option value="">-- Pilih Mesin --</option>';
                if (kode) {
                    fetch(`/teknik/perbaikanteknik/namamesin/get/${kode}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(nama => {
                                let option = document.createElement('option');
                                option.value = nama;
                                option.textContent = nama;
                                namaMesinSelect.appendChild(option);
                            });
                        });
                }
            });

            // Material / Sparepart Dinamis
            const table = document.getElementById('materialTable').getElementsByTagName('tbody')[0];
            table.addEventListener('click', function(e) {
                if (e.target.classList.contains('addRow') || e.target.closest('.addRow')) {
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                <td><input type="text" name="material_sparepart[]" class="form-control form-control-sm"></td>
                <td><input type="number" name="jumlah[]" class="form-control form-control-sm"></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm removeRow">Hapus</button>
                </td>
            `;
                    table.appendChild(newRow);
                }

                if (e.target.classList.contains('removeRow') || e.target.closest('.removeRow')) {
                    const row = e.target.closest('tr');
                    row.remove();
                }
            });
        });
    </script>
@endsection
