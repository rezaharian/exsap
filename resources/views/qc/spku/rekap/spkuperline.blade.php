@php
    // Urutan line secara manual
    $order = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'WESCO'];

    // Ubah hasil query ke bentuk koleksi dan urutkan
    $data = collect($totalspkuperline)
        ->sortBy(function ($item) use ($order) {
            $index = array_search($item['line'], $order);
            return $index === false ? 999 : $index;
        })
        ->values();
@endphp

<b>Total SPKu per line {{ $tahun }}</b>

<div class="d-flex flex-wrap mt-2 mb-4" style="gap: 12px; max-width: 820px;">
    @foreach ($data as $item)
        <div class="card shadow-sm border-0 text-center"
            style="width: 120px; border-radius: 12px; flex: 0 0 calc(16.66% - 10px);">
            <div class="card-body p-2">
                <h6 class="fw-bold mb-1">Line {{ $item['line'] }}</h6>
                <h5 class="fw-bold text-primary mb-0">{{ $item['total_spku'] }}</h5>
            </div>
        </div>
    @endforeach
</div>
