@extends('layouts.app')

@section('content')
    <div class="container my-3 mb-4">
        {{-- Form Pilih Tahun --}}
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            {{-- Tombol Kembali --}}
            <a href="{{ route('teknik.perbaikanteknik.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            {{-- Export Excel --}}
            <form method="GET" action="{{ route('teknik.perbaikanteknik.laporan.tahunan.excel') }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <button type="submit" class="btn btn-sm  mt-3 btn-success">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </button>
            </form>

            {{-- Judul Grafik --}}
            <h5 class="mb-0 text-center flex-grow-1">
                GRAFIK DATA DOWNTIME PER LINE TAHUN {{ $tahun }}
            </h5>

            {{-- Dropdown Tahun --}}
            <form method="GET" action="{{ route('teknik.perbaikanteknik.laporan.tahunan') }}">
                <select name="tahun" id="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                    @php
                        $tahunSekarang = date('Y');
                        $tahunMulai = $tahunSekarang - 5;
                    @endphp
                    @for ($t = $tahunMulai; $t <= $tahunSekarang; $t++)
                        <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>
                            {{ $t }}
                        </option>
                    @endfor
                </select>
            </form>
        </div>

        <div class="row">
            @php $grandTotal = 0; @endphp

            @foreach ($data as $line => $bulanan)
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm rounded-3 p-3 h-100">
                        <h6 class="fw-bold mb-2 text-primary">Line {{ $line }}</h6>
                        <div style="height: 220px;">
                            <canvas id="chart-{{ $line }}"></canvas>
                        </div>

                        {{-- Total Stop Time 1 Tahun per Line --}}
                        @php
                            $totalTahun = array_sum(array_column($bulanan, 'stop_time'));
                            $grandTotal += $totalTahun;
                            $bulanLabels = [
                                'Jan',
                                'Feb',
                                'Mar',
                                'Apr',
                                'Mei',
                                'Jun',
                                'Jul',
                                'Agu',
                                'Sep',
                                'Okt',
                                'Nov',
                                'Des',
                            ];
                        @endphp
                        <p class="mt-3 mb-0 text-muted small">
                            Total Stop Time:
                            <strong class="text-dark">
                                {{ number_format($totalTahun, 2, ',', '.') }} Jam
                            </strong>
                        </p>
                    </div>
                </div>

                <script>
                    const data{{ $line }} = {!! json_encode(array_column($bulanan, 'stop_time')) !!};
                    const maxValue{{ $line }} = Math.max(...data{{ $line }});
                    const ctx{{ $line }} = document.getElementById('chart-{{ $line }}').getContext('2d');

                    new Chart(ctx{{ $line }}, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($bulanLabels) !!},
                            datasets: [{
                                label: 'Stop Time (Jam)',
                                data: data{{ $line }},
                                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                borderRadius: 6,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: {
                                duration: 1200,
                                easing: 'easeOutCubic'
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                datalabels: {
                                    anchor: 'end',
                                    align: 'top',
                                    offset: 4,
                                    clip: false,
                                    color: '#000',
                                    font: {
                                        weight: 'bold',
                                        size: 10
                                    },
                                    formatter: function(value) {
                                        return value > 0 ?
                                            new Intl.NumberFormat('id-ID', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            }).format(value) :
                                            '';
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    suggestedMax: maxValue{{ $line }} + (maxValue{{ $line }} * 0.1),
                                    ticks: {
                                        callback: function(value) {
                                            return new Intl.NumberFormat('id-ID', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            }).format(value);
                                        }
                                    }
                                }
                            }
                        },
                        plugins: [ChartDataLabels]
                    });
                </script>
            @endforeach
        </div>

        {{-- TOTAL SEMUA LINE --}}
        <div class="row mt-4">
            <div class="col text-center">
                <h6 class="fw-bold text-success">
                    Total Stop Time Semua Line Tahun {{ $tahun }} :
                    {{ number_format($grandTotal, 2, ',', '.') }} Jam
                </h6>
            </div>
        </div>
    </div>
@endsection

<!-- CDN jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- CDN Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Plugin untuk label angka di batang -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
