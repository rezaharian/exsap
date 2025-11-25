@php
    // Siapkan data chart di PHP
    $chartDataLine = $spkuperbulan
        ->map(function ($d) {
            return [
                'bulan' => (int) $d['bulan'],
                'line' => $d['line'],
                'total_spku' => (int) $d['total_spku'],
            ];
        })
        ->values()
        ->toArray();
@endphp

<div class="card shadow-sm rounded-3 mt-4" id="chartCardLine">
    {{-- Header Card --}}
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center px-4 pt-3 pb-0">
        <h6 class="fw-bold mb-0">
            Rekap SPKu per Line per Bulan ({{ $tahun ?? date('Y') }})
        </h6>

        <button id="toggleFullscreenLine" class="btn btn-light btn-sm border rounded-circle shadow-sm"
            title="Perbesar Chart">
            <i class="bi bi-arrows-fullscreen"></i>
        </button>
    </div>

    {{-- Isi Card --}}
    <div class="card-body p-4">
        <canvas id="spkuBarChartLine" height="400"></canvas>
    </div>
</div>

{{-- Bootstrap Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

{{-- ChartJS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const rawDataLine = @json($chartDataLine);
        console.log("📊 Data Chart per Line:", rawDataLine);

        const monthNames = [
            '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        const lines = [...new Set(rawDataLine.map(d => d.line))].sort();
        const months = [...new Set(rawDataLine.map(d => d.bulan))].sort((a, b) => a - b);

        const datasetsLine = months.map((bulan, i) => {
            const dataPerLine = lines.map(line => {
                const found = rawDataLine.find(d => d.line === line && d.bulan === bulan);
                return found ? found.total_spku : 0;
            });

            return {
                label: monthNames[bulan],
                data: dataPerLine,
                stack: 'stackLine',
                backgroundColor: `hsl(${i * 30}, 70%, 60%)`,
                borderColor: `hsl(${i * 30}, 70%, 40%)`,
                borderWidth: 1,
                barPercentage: 1.0,
                categoryPercentage: 1.0
            };
        });

        if (window.spkuChartLine instanceof Chart) {
            window.spkuChartLine.destroy();
        }

        const ctxLine = document.getElementById('spkuBarChartLine').getContext('2d');
        window.spkuChartLine = new Chart(ctxLine, {
            type: 'bar',
            data: {
                labels: lines,
                datasets: datasetsLine
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 15
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y}`
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    x: {
                        stacked: true,
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Line',
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah SPKu',
                            font: {
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // === Fullscreen Toggle ===
        const chartCard = document.getElementById('chartCardLine');
        const toggleBtn = document.getElementById('toggleFullscreenLine');
        const icon = toggleBtn.querySelector('i');

        toggleBtn.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                chartCard.requestFullscreen();
                chartCard.classList.add('fullscreen-mode');
                icon.classList.replace('bi-arrows-fullscreen', 'bi-fullscreen-exit');
            } else {
                document.exitFullscreen();
                chartCard.classList.remove('fullscreen-mode');
                icon.classList.replace('bi-fullscreen-exit', 'bi-arrows-fullscreen');
            }
        });
    });
</script>

<style>
    #chartCardLine {
        transition: all 0.3s ease-in-out;
    }

    #chartCardLine.fullscreen-mode {
        background-color: #fff;
        padding: 2rem;
    }

    #toggleFullscreenLine:hover {
        background-color: #f8f9fa;
    }
</style>
