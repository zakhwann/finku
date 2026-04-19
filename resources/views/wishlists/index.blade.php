<x-app-layout>
    <div class="page-header">
        <div>
            <div class="page-title">Wishlist</div>
            <div class="page-sub">Pantau target barang impianmu</div>
        </div>
        <a href="{{ route('wishlists.create') }}" class="btn btn-primary">+ Tambah Wishlist</a>
    </div>

    {{-- Avg Saving Info --}}
    @if($avgMonthlySaving > 0)
    <div class="card" style="margin-bottom:24px;background:#0f1a3a;border-color:#0f1a3a;">
        <div style="display:flex;align-items:center;gap:16px;">
            <div style="width:44px;height:44px;border-radius:12px;background:rgba(46,95,186,0.3);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">◈</div>
            <div style="flex:1;">
                <div style="font-size:13px;font-weight:600;color:#90b4f0;margin-bottom:2px;">Rata-rata Saving Bulanan</div>
                <div style="font-size:11px;color:#5a7ab5;">Berdasarkan 3 bulan terakhir</div>
            </div>
            <div style="text-align:right;">
                <div style="font-family:'Fraunces',serif;font-size:24px;color:#fff;">
                    Rp {{ number_format($avgMonthlySaving, 0, ',', '.') }}
                </div>
                <div style="font-size:11px;color:#5a7ab5;">/bulan</div>
            </div>
        </div>
    </div>
    @endif

    {{-- Wishlist Grid --}}
    @if($wishlists->isEmpty())
    <div class="card" style="text-align:center;padding:48px;">
        <div style="font-size:40px;margin-bottom:16px;">◇</div>
        <div style="font-size:16px;font-weight:600;color:#374151;margin-bottom:8px;">Belum ada wishlist</div>
        <div style="font-size:13px;color:#8a96b0;margin-bottom:20px;">Tambahkan barang impianmu dan mulai menabung!</div>
        <a href="{{ route('wishlists.create') }}" class="btn btn-primary" style="display:inline-flex;">+ Tambah Wishlist</a>
    </div>
    @else

    {{-- Ready to Buy --}}
    @php $readyItems = $wishlists->where('status', 'ready'); @endphp
    @if($readyItems->count() > 0)
    <div style="margin-bottom:8px;">
        <div style="font-size:12px;font-weight:600;color:#059669;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:12px;">
            ✓ Siap Dibeli ({{ $readyItems->count() }})
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
            @foreach($readyItems as $item)
                @include('wishlists._card', ['item' => $item, 'avgMonthlySaving' => $avgMonthlySaving])
            @endforeach
        </div>
    </div>
    @endif

    {{-- Saving --}}
    @php $savingItems = $wishlists->where('status', 'saving'); @endphp
    @if($savingItems->count() > 0)
    <div style="margin-bottom:8px;">
        <div style="font-size:12px;font-weight:600;color:#2e5fba;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:12px;">
            Sedang Ditabung ({{ $savingItems->count() }})
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
            @foreach($savingItems as $item)
                @include('wishlists._card', ['item' => $item, 'avgMonthlySaving' => $avgMonthlySaving])
            @endforeach
        </div>
    </div>
    @endif

    {{-- Purchased --}}
    @php $purchasedItems = $wishlists->where('status', 'purchased'); @endphp
    @if($purchasedItems->count() > 0)
    <div>
        <div style="font-size:12px;font-weight:600;color:#8a96b0;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:12px;">
            Sudah Dibeli ({{ $purchasedItems->count() }})
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
            @foreach($purchasedItems as $item)
                @include('wishlists._card', ['item' => $item, 'avgMonthlySaving' => $avgMonthlySaving])
            @endforeach
        </div>
    </div>
    @endif

    @endif
</x-app-layout>