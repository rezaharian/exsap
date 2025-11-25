<div class="card shadow-sm rounded-3 position-relative" id="spkuJenisCard">
    <div class="d-flex justify-content-between align-items-center p-3 pb-0">
        <b>Rekap SPKu per Jenis {{ $tahun }}</b>
        <!-- 🔍 Tombol fullscreen -->
        <button id="fullscreenJenisBtn" class="btn btn-light btn-sm rounded-circle border">
            <i class="bi bi-arrows-fullscreen"></i>
        </button>
    </div>

    <div class="row align-items-center p-3">
        {{-- Chart --}}
        <div class="col-md-7 mb-4 mb-md-0 text-center">
            <canvas id="spkuPieChartJenisspku" style="max-height: 400px; max-width: 400px;"></canvas>
        </div>

        {{-- Keterangan --}}
        <div class="col-md-5">
            <h6 class="fw-bold mb-3">Keterangan:</h6>
            <ul class="list-group small">
                @foreach ($jenisspku as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-capitalize">{{ $item->jn_lpku }}</span>
                        <span>{{ $item->total_spku }} ({{ $item->persentase }}%)</span>
                    </li>
                @endforeach
                <li class="list-group-item d-flex justify-content-between align-items-center bg-light fw-bold">
                    <span>Total Keseluruhan</span>
                    <span>{{ $jenisspku->sum('total_spku') }} (100%)</span>
                </li>
            </ul>
        </div>
    </div>

    {{-- Footer kecil di bawah chart --}}
    <div class="text-center mt-3 mb-2 small text-muted">
        <em>Data berdasarkan total SPKu yang diinput pada tahun {{ $tahun ?? date('Y') }}.</em>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // 🎨 Data chart
    const ctxJenisspku = document.getElementById('spkuPieChartJenisspku').getContext('2d');

    const dataJenisspku = {
        labels: {!! json_encode($jenisspku->pluck('jn_lpku')) !!},
        datasets: [{
            label: 'Distribusi SPKu',
            data: {!! json_encode($jenisspku->pluck('total_spku')) !!},
            backgroundColor: [
                '#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1', '#fd7e14'
            ],
            borderColor: '#fff',
            borderWidth: 2
        }]
    };

    const optionsJenisspku = {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    font: {
                        size: 12
                    },
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = context.chart._metasets[0].total;
                        const value = context.parsed;
                        const percentage = ((value / total) * 100).toFixed(2);
                        return `${context.label}: ${value} (${percentage}%)`;
                    }
                }
            },
            title: {
                display: true,
                text: 'Persentase SPKu per Jenis',
                font: {
                    size: 14,
                    weight: 'bold'
                }
            }
        }
    };

    // 🥧 Render chart
    const spkuChartJenisspku = new Chart(ctxJenisspku, {
        type: 'pie',
        data: dataJenisspku,
        options: optionsJenisspku
    });

    // 🖥️ Fungsi Fullscreen Toggle
    const fullscreenBtn = document.getElementById('fullscreenJenisBtn');
    const spkuJenisCard = document.getElementById('spkuJenisCard');

    fullscreenBtn.addEventListener('click', () => {
        if (!document.fullscreenElement) {
            spkuJenisCard.requestFullscreen().catch(err => {
                console.error(`Error attempting to enable fullscreen: ${err.message}`);
            });
            fullscreenBtn.innerHTML = `<i class="bi bi-fullscreen-exit"></i>`;
        } else {
            document.exitFullscreen();
            fullscreenBtn.innerHTML = `<i class="bi bi-arrows-fullscreen"></i>`;
        }
    });

    // 🔁 Reset ikon saat keluar fullscreen (misalnya tekan ESC)
    document.addEventListener('fullscreenchange', () => {
        if (!document.fullscreenElement) {
            fullscreenBtn.innerHTML = `<i class="bi bi-arrows-fullscreen"></i>`;
        }
    });
</script>
