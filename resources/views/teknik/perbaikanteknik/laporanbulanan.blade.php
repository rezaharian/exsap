@extends('layouts.app')

@section('content')
    <div class="container my-3">
        <div class="row mb-3">
            <div class="col-md-4">
                <h5 class="mb-3 fw-bold">Laporan Per Line {{ $periodes }}</h5>
            </div>


            <form action="{{ route('teknik.perbaikanteknik.laporan.bulanan') }}" method="GET" class="d-inline col-md-4">
                <select name="periode" class="form-select form-select-sm" onchange="this.form.submit()">
                    @php
                        $now = \Carbon\Carbon::now();
                        $end = $now->copy()->subYears(2);
                    @endphp

                    @for ($date = $now->copy(); $date >= $end; $date->subMonth())
                        @php $val = $date->format('Y-m'); @endphp
                        <option value="{{ $val }}" {{ $periodes == $val ? 'selected' : '' }}>
                            {{ $date->translatedFormat('M Y') }}
                        </option>
                    @endfor
                </select>
            </form>

            <div class="col-md-4">
                <a href="{{ route('teknik.perbaikanteknik.laporan.bulanan.excel', ['periode' => $periodes]) }}"
                    class="btn btn-success btn-sm">
                    Export Excel
                </a>
            </div>
        </div>

        {{-- ========================= PER LINE ========================= --}}
        @foreach ($hasil as $line => $row)
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-header py-2 px-3 bg-primary text-white small">
                    <strong>Line: {{ $line }}</strong>
                    <span class="float-end">Shift: {{ $row['jml_shift'] }} | Prod:
                        {{ number_format($row['prod_qty']) }}</span>
                </div>

                <div class="card-body">
                    <div class="row">

                        <!-- TABEL MESIN -->
                        <div class="col-md-6">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light small">
                                    <tr>
                                        <th>KODE</th>
                                        <th>NAMA MESIN</th>
                                        <th class="text-end">Stop Time</th>
                                        <th class="text-end">Case</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @foreach ($row['detail_mesin'] as $m)
                                        <tr>
                                            <td>{{ $m->kode }}</td>
                                            <td>{{ $m->nama_mesin }}</td>
                                            <td class="text-end">{{ number_format($m->stop_time, 2) }}</td>
                                            <td class="text-end">{{ $m->kasus }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tfoot class="table-light small fw-bold">
                                    <tr>
                                        <th colspan="2" class="text-end">Total</th>
                                        <th class="text-end">{{ number_format($row['total_stop_time'], 2) }}</th>
                                        <th class="text-end">{{ $row['total_kasus'] }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Persentase Downtime</th>
                                        <th class="text-end">{{ number_format($row['persentase_downtime'], 2) }}%</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- CHART -->
                        <div class="col-md-6">
                            <canvas id="chart-{{ Str::slug($line) }}" height="150"></canvas>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach


        {{-- ========================= REKAP PER LINE ========================= --}}
        <div class="card mt-4 border-0 shadow-sm">
            <div class="card-header py-2 px-3 bg-secondary text-white small">
                <strong>Rekapitulasi Per Line</strong>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-6">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead class="table-light small text-center">
                                <tr>
                                    <th>No.</th>
                                    <th>Line</th>
                                    <th>Stop Time</th>
                                    <th>Case</th>
                                    <th>DT (%)</th>
                                </tr>
                            </thead>

                            <tbody class="small">
                                @php $no = 1; @endphp

                                @foreach ($hasil as $line => $row)
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>{{ $line }}</td>
                                        <td class="text-end">{{ number_format($row['total_stop_time'], 2) }}</td>
                                        <td class="text-end">{{ $row['total_kasus'] }}</td>
                                        <td class="text-end">{{ number_format($row['persentase_downtime'], 2) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>

                            {{-- TOTAL --}}
                            <tfoot class="table-light fw-bold small">
                                <tr>
                                    <td colspan="2" class="text-end">TOTAL</td>
                                    <td class="text-end">
                                        {{ number_format(array_sum(array_column($hasil, 'total_stop_time')), 2) }}</td>
                                    <td class="text-end">{{ array_sum(array_column($hasil, 'total_kasus')) }}</td>
                                    <td class="text-end">
                                        {{ number_format(array_sum(array_column($hasil, 'persentase_downtime')) / max(count($hasil), 1), 2) }}%
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                    </div>

                    <div class="col-md-6">
                        <canvas id="rekapChart" height="250"></canvas>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- CDN Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function() {

        // CHART PER LINE
        @foreach ($hasil as $line => $row)
            new Chart(document.getElementById("chart-{{ Str::slug($line) }}"), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($row['detail_mesin']->pluck('nama_mesin')) !!},
                    datasets: [{
                            label: 'Stop Time',
                            data: {!! json_encode($row['detail_mesin']->pluck('stop_time')->map(fn($n) => (float) $n)) !!},
                            backgroundColor: 'rgba(255, 99, 132, 0.6)', // merah
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Case',
                            data: {!! json_encode($row['detail_mesin']->pluck('kasus')->map(fn($n) => (int) $n)) !!},
                            backgroundColor: 'rgba(54, 162, 235, 0.6)', // biru
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }
                    ]

                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        @endforeach


        // CHART REKAP
        new Chart(document.getElementById("rekapChart"), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($hasil)) !!},
                datasets: [{
                        label: 'Total Stop Time',
                        data: {!! json_encode(array_map(fn($i) => (float) $i['total_stop_time'], $hasil)) !!},
                        borderWidth: 1
                    },
                    {
                        label: 'Total Kasus',
                        data: {!! json_encode(array_map(fn($i) => (int) $i['total_kasus'], $hasil)) !!},
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
