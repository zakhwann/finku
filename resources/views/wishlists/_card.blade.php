@php
    $priorityColor = match($item->priority) {
        'high'   => '#dc2626',
        'medium' => '#f59e0b',
        'low'    => '#059669',
    };
    $priorityLabel = match($item->priority) {
        'high'   => 'Tinggi',
        'medium' => 'Sedang',
        'low'    => 'Rendah',
    };

    $estimasiLabel = '';
    if ($item->status === 'saving' && $avgMonthlySaving > 0 && $item->remaining > 0) {
        $bulan = ceil($item->remaining / $avgMonthlySaving);
        $estimasiLabel = $bulan <= 1 ? 'Bulan depan!' : "~{$bulan} bulan lagi";
    }
@endphp

<div class="card" style="position:relative;overflow:hidden;opacity:{{ $item->status === 'purchased' ? '0.6' : '1' }};">

    {{-- Priority Badge --}}
    <div style="position:absolute;top:12px;right:12px;">
        <span style="font-size:10px;font-weight:600;padding:3px 8px;border-radius:20px;background:{{ $priorityColor }}20;color:{{ $priorityColor }};">
            {{ $priorityLabel }}
        </span>
    </div>

    {{-- Image / Placeholder --}}
    @if($item->image_url)
    <img src="{{ $item->image_url }}" alt="{{ $item->name }}"
         style="width:100%;height:140px;object-fit:cover;border-radius:8px;margin-bottom:14px;"
         onerror="this.style.display='none'">
    @else
    <div style="width:100%;height:100px;background:#f0f4fc;border-radius:8px;margin-bottom:14px;display:flex;align-items:center;justify-content:center;font-size:32px;">
        ◇
    </div>
    @endif

    {{-- Info --}}
    <div style="font-size:14px;font-weight:600;color:#1a1f2e;margin-bottom:4px;">{{ $item->name }}</div>
    @if($item->description)
    <div style="font-size:12px;color:#8a96b0;margin-bottom:10px;">{{ $item->description }}</div>
    @endif

    {{-- Price --}}
    <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
        <div>
            <div style="font-size:10px;color:#8a96b0;font-weight:600;text-transform:uppercase;">Target</div>
            <div style="font-family:'Fraunces',serif;font-size:16px;color:#1a1f2e;">Rp {{ number_format($item->target_price, 0, ',', '.') }}</div>
        </div>
        <div style="text-align:right;">
            <div style="font-size:10px;color:#8a96b0;font-weight:600;text-transform:uppercase;">Terkumpul</div>
            <div style="font-family:'Fraunces',serif;font-size:16px;color:#2e5fba;">Rp {{ number_format($item->saved_amount, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Progress Bar --}}
    <div style="height:6px;background:#f0f4fc;border-radius:4px;overflow:hidden;margin-bottom:6px;">
        <div style="height:100%;width:{{ $item->progress }}%;border-radius:4px;background:{{ $item->status === 'ready' || $item->status === 'purchased' ? '#059669' : '#2e5fba' }};transition:width 0.4s;"></div>
    </div>
    <div style="display:flex;justify-content:space-between;margin-bottom:12px;">
        <span style="font-size:11px;color:#8a96b0;">{{ $item->progress }}% terkumpul</span>
        @if($estimasiLabel)
        <span style="font-size:11px;font-weight:600;color:#2e5fba;">{{ $estimasiLabel }}</span>
        @endif
        @if($item->status === 'ready')
        <span style="font-size:11px;font-weight:600;color:#059669;">Siap dibeli! 🎉</span>
        @endif
    </div>

    {{-- Target Date --}}
    @if($item->target_date && $item->status === 'saving')
    <div style="font-size:11px;color:#8a96b0;margin-bottom:10px;">
        Target: {{ $item->target_date->format('d M Y') }}
        @if($item->target_date->isPast())
            <span style="color:#dc2626;">(Terlewat)</span>
        @else
            ({{ $item->target_date->diffForHumans() }})
        @endif
    </div>
    @endif

    {{-- Actions --}}
    @if($item->status === 'saving')
    <form method="POST" action="{{ route('wishlists.addSaving', $item) }}"
          style="display:flex;gap:6px;margin-bottom:8px;">
        @csrf
        <input type="number" name="amount" class="form-control"
               placeholder="Tambah tabungan" min="1"
               style="font-size:12px;padding:6px 10px;">
        <button type="submit" class="btn btn-primary" style="padding:6px 12px;font-size:12px;white-space:nowrap;">
            + Nabung
        </button>
    </form>
    @endif

    @if($item->status === 'ready')
    <form method="POST" action="{{ route('wishlists.purchased', $item) }}"
          style="margin-bottom:8px;">
        @csrf
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;background:#059669;">
            Tandai Sudah Dibeli 🛍️
        </button>
    </form>
    @endif

    <div style="display:flex;gap:6px;">
        @if($item->product_url)
        <a href="{{ $item->product_url }}" target="_blank"
           class="btn btn-secondary" style="padding:5px 10px;font-size:11px;flex:1;justify-content:center;">
            Lihat Produk →
        </a>
        @endif
        <a href="{{ route('wishlists.edit', $item) }}"
           class="btn btn-secondary" style="padding:5px 10px;font-size:11px;">Edit</a>
        <form method="POST" action="{{ route('wishlists.destroy', $item) }}"
              onsubmit="return confirm('Hapus wishlist ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger" style="padding:5px 10px;font-size:11px;">✕</button>
        </form>
    </div>
</div>