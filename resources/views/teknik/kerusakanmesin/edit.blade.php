@extends('layouts.app')

@section('content')
    <div class="container">
        <h4 class="mb-3">Edit Kerusakan Mesin</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('teknik.kerusakanmesin.update', $data->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- HEADER DATA -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Kode Problem</label>
                            <input type="text" name="prob_cod" class="form-control" value="{{ $data->prob_cod }}"
                                readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Input</label>
                            <input type="date" name="tgl_input" class="form-control" value="{{ $data->tgl_input }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Line</label>
                            <select name="line" class="form-select select2" required>
                                <option value=""></option>
                                @foreach ($datal as $item)
                                    <option value="{{ $item->LINE }}"
                                        {{ trim($data->line) == trim($item->LINE) ? 'selected' : '' }}>
                                        {{ $item->LINE }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Unit Mesin</label>
                            <select name="unitmesin" class="form-select select2">
                                <option value=""></option>
                                @foreach ($datau as $item)
                                    <option value="{{ $item->unit_nam }}"
                                        {{ trim($data->unitmesin) == trim($item->unit_nam) ? 'selected' : '' }}>
                                        {{ $item->unit_nam }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Masalah</label>
                        <textarea name="masalah" class="form-control" rows="3">{{ $data->masalah }}</textarea>
                    </div>

                    <div class="mt-3 row">
                        @for ($i = 1; $i <= 4; $i++)
                            @php $imgField = 'img_pro0' . $i; @endphp
                            <div class="col-md-3">
                                <label class="form-label">Gambar {{ $i }}</label>
                                <input type="file" name="img_pro0{{ $i }}" class="form-control"
                                    id="img_pro0{{ $i }}">

                                @php
                                    $imgPath = $data->$imgField ?? 'No Image';
                                    $imgUrl = $imgPath !== 'No Image' ? asset('storage/' . $imgPath) : '#';
                                    $imgStyle = $imgPath !== 'No Image' ? 'display:block;' : 'display:none;';
                                @endphp

                                <img id="preview{{ $i }}" src="{{ $imgUrl }}"
                                    style="max-width:150px; margin-top:5px; {{ $imgStyle }}">
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
                            @php $rowCount = 0; @endphp
                            @foreach ($data->details as $detail)
                                @php $rowCount++; @endphp
                                <tr>
                                    <td class="text-center">{{ $rowCount }}</td>
                                    <td><input type="text" name="id_no[]" class="form-control"
                                            value="{{ $detail->id_no }}" readonly></td>
                                    <td>
                                        <textarea name="penyebab[]" class="form-control" rows="1">{{ $detail->penyebab }}</textarea>
                                    </td>
                                    <td>
                                        <textarea name="perbaikan[]" class="form-control" rows="1">{{ $detail->perbaikan }}</textarea>
                                    </td>
                                    <td><input type="date" name="tgl_rpr[]" class="form-control"
                                            value="{{ $detail->tgl_rpr }}"></td>
                                    <td>
                                        <textarea name="pencegahan[]" class="form-control" rows="1">{{ $detail->pencegahan }}</textarea>
                                    </td>
                                    <td><input type="date" name="tgl_pre[]" class="form-control"
                                            value="{{ $detail->tgl_pre }}"></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger removeRow">Hapus</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <button class="btn btn-primary" type="submit">Update</button>
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
            let detailIdNoCounter = rowCount; // melanjutkan dari jumlah detail saat ini
            let baseNo = "{{ $data->prob_cod }}";

            $('#addRow').click(function() {
                rowCount++;
                detailIdNoCounter++;
                let newIdNo = baseNo + detailIdNoCounter;

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
    <script>
        $(document).ready(function() {
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
        });
    </script>
@endsection
