@extends('layouts.app')

@section('content')
    <div class="">
        {{-- 🔔 Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show small">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger small">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h5 class="fw-semibold text-center mb-4">Edit SPKu</h5>

        <form action="{{ route('qc.spku.update', $spku->id) }}" method="POST" class="px-1">
            @csrf
            @method('PUT')

            {{-- 🔹 Header --}}
            <div class="card mb-3 p-2 small">
                {{-- Baris 1 --}}
                <div class="row mb-2">
                    <div class="col-md-2 fw-bold">Kode SPKu:</div>
                    <div class="col-md-4">
                        <input type="text" name="spku_cod" value="{{ $spku->spku_cod }}"
                            class="form-control form-control-sm" readonly>
                    </div>

                    <div class="col-md-2 fw-bold">Shift:</div>
                    <div class="col-md-4">
                        <select name="shift" class="form-control form-control-sm" required>
                            <option value="">-- Pilih Shift --</option>
                            @foreach (['I', 'II', 'III'] as $s)
                                <option value="{{ $s }}" {{ $spku->shift == $s ? 'selected' : '' }}>
                                    {{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Baris 2 --}}
                <div class="row mb-2">
                    <div class="col-md-2 fw-bold">No SPK:</div>
                    <div class="col-md-4">
                        <input type="text" name="spk_nomor" value="{{ $spku->spk_nomor }}"
                            class="form-control form-control-sm" required readonly>
                    </div>

                    <div class="col-md-2 fw-bold">Tanggal Input:</div>
                    <div class="col-md-4">
                        <input type="date" name="tgl_input"
                            value="{{ \Carbon\Carbon::parse($spku->tgl_input)->format('Y-m-d') }}"
                            class="form-control form-control-sm" required>
                    </div>
                </div>

                {{-- Baris 3 --}}
                <div class="row mb-2">
                    <div class="col-md-2 fw-bold">Produk:</div>
                    <div class="col-md-4">
                        <input type="text" name="produc_nam" value="{{ $spku->produc_nam }}"
                            class="form-control form-control-sm" readonly required>
                    </div>

                    <div class="col-md-2 fw-bold">Jam:</div>
                    <div class="col-md-4">
                        <input type="time" name="jam" value="{{ $spku->jam }}"
                            class="form-control form-control-sm" required>
                    </div>
                </div>

                {{-- Baris 4 --}}
                <div class="row mb-2">
                    <div class="col-md-2 fw-bold">Customer:</div>
                    <div class="col-md-4">
                        <input type="text" name="custom_nam" value="{{ $spku->custom_nam }}"
                            class="form-control form-control-sm" readonly required>
                    </div>

                    <div class="col-md-2 fw-bold">Operator:</div>
                    <div class="col-md-4">
                        <select name="operator" class="form-control form-control-sm operator-select" required>
                            @if ($spku->operator)
                                <option value="{{ $spku->operator }}" selected>{{ $spku->operator }}</option>
                            @endif
                        </select>
                    </div>
                </div>

                {{-- Baris 5 --}}
                <div class="row mb-2">
                    <div class="col-md-2 fw-bold">Line:</div>
                    <div class="col-md-4">
                        <input type="text" name="line" value="{{ $spku->line }}"
                            class="form-control form-control-sm" readonly required>
                    </div>

                    <div class="col-md-2 fw-bold">Jenis Laporan:</div>
                    <div class="col-md-4">
                        <select name="jn_lpku" class="form-control form-control-sm" required>
                            <option value="">-- Pilih Jenis Laporan --</option>
                            @foreach (['proses' => 'proses', 'random' => 'random'] as $key => $val)
                                <option value="{{ $key }}" {{ $spku->Jn_lpku == $key ? 'selected' : '' }}>
                                    {{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Baris 6 --}}
                <div class="row mb-1">
                    <div class="col-md-2 fw-bold">Ukuran:</div>
                    <div class="col-md-4">
                        <input type="text" name="produc_uk" value="{{ $spku->produc_uk }}"
                            class="form-control form-control-sm" readonly required>
                    </div>

                    <div class="col-md-2 fw-bold">Unit:</div>
                    <div class="col-md-4">
                        <select name="unit" class="form-control form-control-sm" required>
                            <option value="">-- Pilih Unit --</option>
                            @foreach (['Extruder & Drad', 'BC & Printing', 'Anneling & IC', 'Finishing'] as $u)
                                <option value="{{ $u }}" {{ $spku->unit == $u ? 'selected' : '' }}>
                                    {{ $u }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Baris 7 --}}
                <div class="row mb-0">
                    <div class="col-md-2 fw-bold">Keterangan:</div>
                    <div class="col-md-4">
                        <input type="text" name="keterangan" value="{{ $spku->keterangan }}"
                            class="form-control form-control-sm">
                    </div>

                    <div class="col-md-2 fw-bold">Dilaporkan:</div>
                    <div class="col-md-4">
                        <select name="dilaporkan" class="form-control form-control-sm dilaporkan-select" required>
                            @if ($spku->dilaporkan)
                                <option value="{{ $spku->dilaporkan }}" selected>{{ $spku->dilaporkan }}</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            {{-- 🔹 Detail --}}
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle small" id="detailTable">
                    <thead class="table-light text-center">
                        <tr>
                            <th hidden>INT</th>
                            <th>Kd Grup</th>
                            <th>Jns Penyimpangan</th>
                            <th>Penyebab</th>
                            <th>Perbaikan</th>
                            <th>Tgl Perbaikan</th>
                            <th>Pencegahan</th>
                            <th>Tgl Pre</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($spku->details as $d)
                            <tr>
                                <td hidden>
                                    <input type="text" name="int[]" value="{{ $d->int ?? Str::random(5) }}"
                                        class="form-control form-control-sm">
                                </td>
                                <td>
                                    <select name="kd_grup[]" class="form-control form-control-sm">
                                        <option value="">-- Pilih Grup --</option>
                                        @foreach ($jnpn as $g)
                                            <option value="{{ $g->kd_grup }}"
                                                {{ $d->kd_grup == $g->kd_grup ? 'selected' : '' }}>
                                                {{ $g->penyimpangan }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <textarea name="jn_penyimpangan[]" class="form-control form-control-sm">{{ $d->jn_penyimpangan }}</textarea>
                                </td>
                                <td>
                                    <textarea name="penyebab[]" class="form-control form-control-sm">{{ $d->penyebab }}</textarea>
                                </td>
                                <td>
                                    <textarea name="perbaikan[]" class="form-control form-control-sm">{{ $d->perbaikan }}</textarea>
                                </td>
                                <td><input type="date" name="tgl_perbaikan[]" class="form-control form-control-sm"
                                        value="{{ $d->tgl_perbaikan ? $d->tgl_perbaikan->format('Y-m-d') : '' }}"></td>
                                <td>
                                    <textarea name="pencegahan[]" class="form-control form-control-sm">{{ $d->pencegahan }}</textarea>
                                </td>
                                <td><input type="date" name="tgl_pre[]" class="form-control form-control-sm"
                                        value="{{ $d->tgl_pre ? $d->tgl_pre->format('Y-m-d') : '' }}"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger removeRow">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-2">
                <button type="button" class="btn btn-sm btn-primary" id="addRow">+ Tambah Baris</button>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-md rounded-pill shadow-sm">Update</button>
            </div>
        </form>
    </div>

    {{-- 🔹 Select2 dan JS --}}
    <script>
        $(document).ready(function() {
            // 🔹 Operator Select2
            function initSelect2Operator(el) {
                $(el).select2({
                    placeholder: '-- Pilih Operator --',
                    allowClear: true,
                    width: 'resolve',
                    minimumInputLength: 2,
                    ajax: {
                        url: "{{ route('autocomplete.spk') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                query: params.term,
                                type: 'pegawai'
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    }
                });
            }

            // 🔹 Dilaporkan Select2
            function initSelect2Dilaporkan(el) {
                $(el).select2({
                    placeholder: '-- Pilih Dilaporkan Oleh --',
                    allowClear: true,
                    width: 'resolve',
                    minimumInputLength: 2,
                    ajax: {
                        url: "{{ route('autocomplete.spk') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                query: params.term,
                                type: 'pegawai'
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    }
                });
            }

            // 🔹 Inisialisasi
            initSelect2Operator('select[name="operator"]');
            initSelect2Dilaporkan('select[name="dilaporkan"]');

            // 🔹 Tambah Baris
            $('#addRow').click(function() {
                let randInt = Math.random().toString(36).substring(2, 7);
                let row = `<tr>
                    <td hidden><input type="text" name="int[]" value="${randInt}" class="form-control form-control-sm"></td>
                    <td><select name="kd_grup[]" class="form-control form-control-sm">
                        <option value="">-- Pilih Grup --</option>
                        @foreach ($jnpn as $g)
                            <option value="{{ $g->kd_grup }}">{{ $g->penyimpangan }}</option>
                        @endforeach
                    </select></td>
                    <td><textarea name="jn_penyimpangan[]" class="form-control form-control-sm"></textarea></td>
                    <td><textarea name="penyebab[]" class="form-control form-control-sm"></textarea></td>
                    <td><textarea name="perbaikan[]" class="form-control form-control-sm"></textarea></td>
                    <td><input type="date" name="tgl_perbaikan[]" class="form-control form-control-sm"></td>
                    <td><textarea name="pencegahan[]" class="form-control form-control-sm"></textarea></td>
                    <td><input type="date" name="tgl_pre[]" class="form-control form-control-sm"></td>
                    <td class="text-center"><button type="button" class="btn btn-sm btn-danger removeRow">Hapus</button></td>
                </tr>`;
                $('#detailTable tbody').append(row);
            });

            // 🔹 Hapus Baris
            $('#detailTable').on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>
@endsection
