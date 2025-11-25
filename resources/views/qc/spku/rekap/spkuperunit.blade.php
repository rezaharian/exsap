<div class="card shadow-sm border-0 p-4 rounded-4 mt-2">
    <b>Rekap SPKu per Unit {{ $tahun }}</b>

    <div class="row mt-3 align-items-start">
        {{-- 🥧 Kolom kiri: Chart --}}
        <div class="col-md-6 text-center">
            <div class="p-4">
                <canvas id="spkuPieChart" style="max-height: 400px; max-width: 400px;"></canvas>
            </div>
        </div>

        {{-- 📋 Kolom kanan: Keterangan --}}
        <div class="col-md-6">
            <div id="spkuInfo" class="p-3 rounded-4 border bg-light small shadow-sm"
                style="max-height: 480px; overflow-y: auto;">
            </div>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
    const ctx = document.getElementById('spkuPieChart');
    const infoContainer = document.getElementById('spkuInfo');

    // Data dari controller
    const spkuData = @json($spkuperunit);

    const labels = spkuData.map(item => item.unit);
    const percentages = spkuData.map(item => item.persen);
    const totals = spkuData.map(item => item.total);

    const backgroundColors = ['#6f42c1', '#0d6efd', '#dc3545', '#20c997', '#fd7e14', '#198754'];

    // Buat chart pie
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: percentages,
                backgroundColor: backgroundColors.slice(0, spkuData.length),
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: 40,
                    bottom: 20
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                datalabels: {
                    color: '#000',
                    formatter: (value, context) => {
                        const label = context.chart.data.labels[context.dataIndex];
                        return `${label}\n(${value}%)`;
                    },
                    anchor: 'end',
                    align: 'end',
                    offset: 12,
                    font: {
                        weight: 'bold',
                        size: 11
                    }
                }
            }
        },
        plugins: [ChartDataLabels],
    });

    // 🔍 Keterangan dalam 2 kolom
    let infoHTML = `
        <h6 class="fw-bold mb-3 text-center">
            <b>Keterangan :</b>
        </h6>
        <div class="row row-cols-1 row-cols-md-2 g-3">
    `;

    spkuData.forEach((unit, index) => {
        const color = backgroundColors[index];
        infoHTML += `
            <div class="col">
                <div class="p-2 border rounded bg-white shadow-sm h-100">
                    <div class="d-flex align-items-center mb-1">
                        <i class="bi bi-building me-2 text-secondary"></i>
                        <strong style="color:${color}">${unit.unit}</strong>
                    </div>
                    <div class="ms-3 small">
                        <i class="bi bi-file-earmark-text text-muted"></i>
                        Jumlah SPKu: <b>${unit.total}</b> (${unit.persen}%)
                        <br>
                        <u>Problem terbanyak:</u><br>
                        ${unit.problems.map((p, i) => `<span class="ms-3">${i + 1}. ${p}</span>`).join('<br>')}
                    </div>
                </div>
            </div>
        `;
    });

    infoHTML += `</div>`;
    infoContainer.innerHTML = infoHTML;
</script>
