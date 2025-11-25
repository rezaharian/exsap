@extends('admin.layouts.master')

@section('content')
    {{-- 🔍 Filter Tahun + Tombol Cetak --}}
    <form action="{{ route('ad.spku.rekap') }}" method="GET" class="mb-3 d-print-none">
        <div class="row g-2 align-items-center justify-content-between">
            {{-- Kolom kiri: Filter Tahun --}}
            <div class="col-auto d-flex align-items-center">
                <label for="tahun" class="form-label small fw-bold mb-0 me-2">Tahun:</label>
                <select name="tahun" id="tahun" class="form-control form-control-sm" onchange="this.form.submit()">
                    @php
                        $currentYear = date('Y');
                    @endphp
                    @for ($year = $currentYear; $year >= 2000; $year--)
                        <option value="{{ $year }}" {{ isset($tahun) && $tahun == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endfor
                </select>
            </div>

            {{-- Kolom kanan: Tombol Cetak --}}
            <div class="col-auto">
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="printContainer()">
                    <i class="bi bi-printer me-1"></i> Cetak
                </button>
            </div>
        </div>
    </form>

    {{-- 📊 Hanya bagian ini yang akan dicetak --}}
    <div id="printArea" class="container mt-4">
        <div class="mb-4">
            @include('admin.input.spku.rekap.spkuperline')
        </div>

        <div class="mb-4">
            @include('admin.input.spku.rekap.spkuperjenis')
        </div>

        <div class="mb-4">
            @include('admin.input.spku.rekap.spkuperunit')
        </div>

        <div class="mb-4">
            @include('admin.input.spku.rekap.spkuperbulan')
        </div>

        <div class="mb-4">
            @include('admin.input.spku.rekap.spkuperlinechart')
        </div>
    </div>

    {{-- 📜 Script agar hanya container tercetak dan chart tidak hilang --}}
    <script>
        function printContainer() {
            // 1️⃣ Ubah semua canvas jadi gambar agar Chart.js tidak hilang
            document.querySelectorAll('#printArea canvas').forEach(canvas => {
                const img = document.createElement('img');
                img.src = canvas.toDataURL('image/png');
                img.classList.add('chart-image');
                img.style.maxWidth = '100%';
                canvas.style.display = 'none';
                canvas.parentNode.insertBefore(img, canvas.nextSibling);
            });

            // 2️⃣ Tunggu sebentar agar gambar sempat dirender, baru print
            setTimeout(() => {
                window.print();

                // 3️⃣ Setelah cetak, hapus gambar dan tampilkan kembali canvas
                document.querySelectorAll('.chart-image').forEach(img => img.remove());
                document.querySelectorAll('#printArea canvas').forEach(canvas => canvas.style.display = 'block');
            }, 300);
        }
    </script>

    {{-- 🎨 Styling khusus untuk mode cetak --}}
    <style>
        @media print {

            /* Hanya tampilkan area #printArea */
            body * {
                visibility: hidden !important;
            }

            #printArea,
            #printArea * {
                visibility: visible !important;
            }

            #printArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 1cm;
                margin: 0;
            }

            /* Hilangkan shadow, border, tombol, dsb */
            .card,
            .container {
                box-shadow: none !important;
                border: none !important;
            }

            .d-print-none {
                display: none !important;
            }

            /* Atur tampilan rapi di PDF */
            h3,
            h4,
            h5 {
                font-weight: bold;
                text-align: center;
                margin-bottom: 10px;
            }
        }
    </style>
@endsection
