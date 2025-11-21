@extends('layouts.app')

@section('content')
    <div class="section">
        <div class="row">
            <div class="row text-center mb-3">
                <div class="col-12">
                    <h5 class="font-weight-bolder mb-0 text-center ml-3">Daftar Masalah Mesin</h5>
                </div>
            </div>

            <div class="col-md-10">
                <div class="row">

                    <div class="col-md-2 ">
                        <form class="form-group"action="{{ route('teknik.perbaikanteknik.laporan.daftarmasalahmesin') }}"
                            method="GET">
                            <select
                                class="form-control form-control-sm font-weight-bold text-secondary  rounded border-primary"
                                name="cariline" placeholder="line ">
                                <option selected value="">SEMUA</option>
                                @foreach ($datal as $item)
                                    <option value="{{ $item->LINE }}">{{ $item->LINE }}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="col-md-2 ">
                        <select
                            class="form-control form-control-sm font-weight-bold text-secondary  rounded border-primary "
                            name="cariunitmsn" placeholder="unit ">
                            <option selected value="">SEMUA</option>
                            @foreach ($datau as $item)
                                <option value="{{ $item->unit_nam }}">{{ $item->unit_nam }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text"
                            class="form-control form-control-sm font-weight-bold text-secondary border-primary"
                            name="masalah" placeholder="masalah">
                    </div>
                    <div class="col-md-3">
                        <input type="text"
                            class="form-control form-control-sm font-weight-bold text-secondary border-primary"
                            name="penyebab" placeholder="penyebab">
                    </div>
                    <div class="col-md-1 ">
                        <input type="submit" class="btn btn-primary btn-sm border text-light" value="CARI">
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-1">
        </div>
    </div>
    <div class="row mt-1">
        <div class="col-md-12">
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (Session::has('success'))
                <div class="alert alert-success text-center">
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif
            <div class="card p-2 ">
                <div style="height:450px;overflow:auto;">
                    <table id="example" class="display align-items-center mb-0 table-bordered table-small">
                        <thead>

                            <tr class="text-xs">
                                <th style="width:7%;">No Doc</th>
                                <th style="width: 5%;">Line</th>
                                <th style="width: 12%;">Unit Mesin</th>
                                <th style="width:15%;">Masalah</th>
                                <th style="width:15%;">Penyebab</th>
                                <th style="width:15%;">Perbaikan</th>
                                <th style="width:15%;">Pencegahan</th>
                                <th style="width:7%;">TGL</th>
                                <th style="width:4%; text-align:center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($list as $item)
                                <tr class=" text-xs  ">
                                    <td>{{ $item->prob_cod }}</td>
                                    <td class="text-xs">{{ $item->line }}</td>
                                    <td>{{ $item->unitmesin }}</td>
                                    <td>
                                        {{ $item->masalah }}
                                    </td>
                                    <td>
                                        {{ $item->penyebab }}
                                    </td>
                                    <td>
                                        {{ $item->perbaikan }}
                                    </td>
                                    <td>
                                        {{ $item->pencegahan }}
                                    </td>
                                    <td>{{ $item->tgl_input }}</td>
                                    <td style="text-align: center;">
                                        <a
                                            href="{{ route('teknik.perbaikanteknik.laporan.daftarmasalahmesin_d', $item->id) }}">
                                            <button type="button" class="btn btn-primary btn-sm m-0 ">Lihat</button>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <div class="alert alert-danger">
                                    Data belum Tersedia.
                                </div>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "order": [
                    [0, "desc"]
                ] // Change the column index (0) to the column you want to sort by
            });
        });
    </script>


@endsection
