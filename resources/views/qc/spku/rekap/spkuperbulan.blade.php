@php
    // Siapkan data chart di PHP
    $chartData = $spkuperbulan
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

<div class="card shadow-sm rounded-3" id="chartCardBar">
    {{-- Header Card --}}
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center px-4 pt-3 pb-0">
        <h6 class="fw-bold mb-0">
            Rekap SPKu per Bulan per Line ({{ $tahun ?? date('Y') }})
        </h6>

        <button id="toggleFullscreenBar" class="btn btn-light btn-sm border rounded-circle shadow-sm"
            title="Perbesar Chart">
            <i class="bi bi-arrows-fullscreen"></i>
        </button>
    </div>

    {{-- Isi Card --}}
    <div class="card-body p-4">
        <canvas id="spkuBarChart" height="400"></canvas>
    </div>
</div>

{{-- Bootstrap Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

{{-- ChartJS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const rawData = @json($chartData);
        const monthNames = [
            '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        const labels = monthNames.slice(1);

        const lines = [...new Set(rawData.map(d => d.line))].sort();

        const datasets = lines.map((line, i) => {
            const dataPerBulan = Array.from({
                length: 12
            }, (_, idx) => {
                const bulan = idx + 1;
                const found = rawData.find(d => d.bulan === bulan && d.line === line);
                return found ? found.total_spku : 0;
            });

            return {
                label: `Line ${line}`,
                data: dataPerBulan,
                stack: 'stack1',
                backgroundColor: `hsl(${i * 35}, 70%, 60%)`,
                borderColor: `hsl(${i * 35}, 70%, 40%)`,
                borderWidth: 1,
                barPercentage: 1.0,
                categoryPercentage: 1.0
            };
        });

        if (window.spkuChart instanceof Chart) {
            window.spkuChart.destroy();
        }

        const ctx = document.getElementById('spkuBarChart').getContext('2d');
        window.spkuChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets
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
                            text: 'Bulan',
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
        const chartCard = document.getElementById('chartCardBar');
        const toggleBtn = document.getElementById('toggleFullscreenBar');
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
    /* Efek transisi halus ketika fullscreen */
    #chartCardBar {
        transition: all 0.3s ease-in-out;
    }

    #chartCardBar.fullscreen-mode {
        background-color: #fff;
        padding: 2rem;
    }

    #toggleFullscreenBar:hover {
        background-color: #f8f9fa;
    }
</style>
