@extends('layouts.app')

@section('content')
    <div class="container">
        <h4 class="mb-3">Tambah Kerusakan Mesin</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('teknik.kerusakanmesin.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- HEADER DATA -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="prob_cod" class="form-label">Kode Problem</label>
                            <input type="text" name="prob_cod" class="form-control" value="{{ $no }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="tgl_input" class="form-label">Tanggal Input</label>
                            <input type="date" name="tgl_input" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="line" class="form-label">Line</label>
                            <select name="line" class="form-select select2" required>
                                <option selected value=""></option>
                                @foreach ($datal as $item)
                                    <option value="{{ $item->LINE }}">{{ $item->LINE }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="unitmesin" class="form-label">Unit Mesin</label>
                            <select name="unitmesin" class="form-select select2">
                                <option value=""></option>
                                @foreach ($datau as $item)
                                    <option value="{{ $item->unit_nam }}">{{ $item->unit_nam }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="masalah" class="form-label">Masalah</label>
                        <textarea name="masalah" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mt-3 row">
                        @for ($i = 1; $i <= 4; $i++)
                            <div class="col-md-3">
                                <label class="form-label">Gambar {{ $i }}</label>
                                <input type="file" name="img_pro0{{ $i }}" class="form-control"
                                    id="img_pro0{{ $i }}">
                                <img id="preview{{ $i }}" src="#" alt="Preview"
                                    style="max-width:150px; display:none; margin-top:5px;">
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- DETAIL DATA -->
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detail Perbaikan</span>
                    <button type="button" class="btn btn-sm btn-success" id="addRow">Tambah Baris</button>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0" id="detailTable">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>ID No</th>
                                <th>Penyebab</th>
                                <th>Perbaikan</th>
                                <th>Tgl Perbaikan</th>
                                <th>Pencegahan</th>
                                <th>Tgl Pencegahan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td>
                                    <input type="text" name="id_no[]" class="form-control" value="{{ $no }}1"
                                        readonly>
                                </td>
                                <td>
                                    <textarea name="penyebab[]" class="form-control" rows="1"></textarea>
                                </td>
                                <td>
                                    <textarea name="perbaikan[]" class="form-control" rows="1"></textarea>
                                </td>
                                <td><input type="date" name="tgl_rpr[]" class="form-control"></td>
                                <td>
                                    <textarea name="pencegahan[]" class="form-control" rows="1"></textarea>
                                </td>
                                <td><input type="date" name="tgl_pre[]" class="form-control"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger removeRow">Hapus</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <button class="btn btn-primary" type="submit">Simpan</button>
            <a href="{{ route('teknik.kerusakanmesin.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            // Preview gambar
            for (let i = 1; i <= 4; i++) {
                $('#img_pro0' + i).on('change', function(e) {
                    const [file] = e.target.files;
                    if (file) {
                        const preview = $('#preview' + i);
                        preview.attr('src', URL.createObjectURL(file));
                        preview.show();
                    }
                });
            }

            // Detail table
            let rowCount = $('#detailTable tbody tr').length;
            let detailIdNoCounter = 1; // angka tambahan setelah kode problem

            $('#addRow').click(function() {
                rowCount++;
                detailIdNoCounter++;

                let baseNo = "{{ $no }}"; // kode problem
                let newIdNo = baseNo + detailIdNoCounter; // contohnya 2501022, 2501023

                let newRow = `<tr>
                <td class="text-center">${rowCount}</td>
                <td><input type="text" name="id_no[]" value="${newIdNo}" class="form-control" readonly></td>
                <td><textarea name="penyebab[]" class="form-control" rows="1"></textarea></td>
                <td><textarea name="perbaikan[]" class="form-control" rows="1"></textarea></td>
                <td><input type="date" name="tgl_rpr[]" class="form-control"></td>
                <td><textarea name="pencegahan[]" class="form-control" rows="1"></textarea></td>
                <td><input type="date" name="tgl_pre[]" class="form-control"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger removeRow">Hapus</button>
                </td>
            </tr>`;

                $('#detailTable tbody').append(newRow);
            });

            $(document).on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
                $('#detailTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
                rowCount = $('#detailTable tbody tr').length;
            });
        });
    </script>
@endsection
