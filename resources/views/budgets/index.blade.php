<x-app-layout>
    <div class="page-header">
        <div>
            <div class="page-title">Budget Bulanan</div>
            <div class="page-sub">Atur batas pengeluaran per kategori</div>
        </div>
    </div>

    {{-- Pilih Bulan --}}
    <div class="card" style="margin-bottom:20px;">
        <form method="GET" action="{{ route('budgets.index') }}"
              style="display:flex;gap:12px;align-items:flex-end;">
            <div>
                <label class="form-label">Bulan</label>
                <select name="month" class="form-control" style="width:140px;">
                    @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$m] }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Tahun</label>
                <select name="year" class="form-control" style="width:100px;">
                    @foreach([now()->year - 1, now()->year, now()->year + 1] as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">Tampilkan</button>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
        <div class="card" style="background:#0f1a3a;border-color:#0f1a3a;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#5a7ab5;margin-bottom:10px;font-weight:600;">Total Budget</div>
            <div style="font-family:'Fraunces',serif;font-size:26px;color:#fff;letter-spacing:-1px;">
                Rp {{ number_format($totalBudget, 0, ',', '.') }}
            </div>
        </div>
        <div class="card" style="border-top:3px solid #dc2626;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#8a96b0;margin-bottom:10px;font-weight:600;">Terpakai</div>
            <div style="font-family:'Fraunces',serif;font-size:26px;color:#dc2626;letter-spacing:-1px;">
                Rp {{ number_format($totalRealisasi, 0, ',', '.') }}
            </div>
        </div>
        <div class="card" style="border-top:3px solid #059669;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#8a96b0;margin-bottom:10px;font-weight:600;">Sisa Budget</div>
            @php $sisa = $totalBudget - $totalRealisasi; @endphp
            <div style="font-family:'Fraunces',serif;font-size:26px;letter-spacing:-1px;color:{{ $sisa >= 0 ? '#059669' : '#dc2626' }};">
                Rp {{ number_format(abs($sisa), 0, ',', '.') }}
                {{ $sisa < 0 ? '(Melebihi!)' : '' }}
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 380px;gap:20px;">

        {{-- Form Set Budget --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Set Budget per Kategori</div>
                <div style="font-size:12px;color:#8a96b0;">
                    {{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$month] }} {{ $year }}
                </div>
            </div>

            @if($categories->isEmpty())
                <div style="text-align:center;padding:30px;color:#8a96b0;font-size:13px;">
                    Belum ada kategori pengeluaran. 
                    <a href="{{ route('categories.create') }}" style="color:#2e5fba;">Tambah kategori →</a>
                </div>
            @else
            <form method="POST" action="{{ route('budgets.store') }}">
                @csrf
                <input type="hidden" name="month" value="{{ $month }}">
                <input type="hidden" name="year"  value="{{ $year }}">

                @foreach($categories as $i => $cat)
                <input type="hidden" name="budgets[{{ $i }}][category_id]" value="{{ $cat->id }}">
                <div style="display:flex;align-items:center;gap:16px;padding:12px 0;border-bottom:1px solid #f0f4fc;">
                    <div style="display:flex;align-items:center;gap:8px;width:160px;flex-shrink:0;">
                        <div style="width:10px;height:10px;border-radius:50%;background:{{ $cat->color }};flex-shrink:0;"></div>
                        <span style="font-size:13px;font-weight:500;">{{ $cat->name }}</span>
                    </div>
                    <div style="flex:1;">
                        <input type="number"
                               name="budgets[{{ $i }}][amount]"
                               class="form-control"
                               placeholder="0"
                               min="0"
                               value="{{ isset($budgets[$cat->id]) ? $budgets[$cat->id]->amount : '' }}">
                    </div>
                    @if(isset($realisasi[$cat->id]))
                    <div style="font-size:12px;color:#8a96b0;flex-shrink:0;width:100px;text-align:right;">
                        Terpakai:<br>
                        <span style="color:#dc2626;font-weight:500;">Rp {{ number_format($realisasi[$cat->id], 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
                @endforeach

                <button type="submit" class="btn btn-primary" style="margin-top:20px;width:100%;justify-content:center;">
                    Simpan Budget
                </button>
            </form>
            @endif
        </div>

        {{-- Progress per Kategori --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Progress Pengeluaran</div>
            </div>

            @if($budgets->isEmpty())
                <div style="text-align:center;padding:30px;color:#8a96b0;font-size:13px;">
                    Belum ada budget yang diset bulan ini.
                </div>
            @else
                @foreach($categories as $cat)
                @if(isset($budgets[$cat->id]))
                @php
                    $budget     = $budgets[$cat->id]->amount;
                    $terpakai   = $realisasi[$cat->id] ?? 0;
                    $pct        = $budget > 0 ? min(round(($terpakai / $budget) * 100), 100) : 0;
                    $over       = $terpakai > $budget;
                    $barColor   = $pct >= 90 ? '#dc2626' : ($pct >= 70 ? '#f59e0b' : '#2e5fba');
                @endphp
                <div style="margin-bottom:18px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                        <div style="display:flex;align-items:center;gap:7px;">
                            <div style="width:8px;height:8px;border-radius:50%;background:{{ $cat->color }};"></div>
                            <span style="font-size:13px;font-weight:500;">{{ $cat->name }}</span>
                        </div>
                        <span style="font-size:12px;color:{{ $over ? '#dc2626' : '#8a96b0' }};font-weight:{{ $over ? '600' : '400' }};">
                            {{ $pct }}% {{ $over ? '⚠️' : '' }}
                        </span>
                    </div>
                    <div style="height:7px;background:#f0f4fc;border-radius:4px;overflow:hidden;margin-bottom:5px;">
                        <div style="height:100%;width:{{ $pct }}%;background:{{ $barColor }};border-radius:4px;transition:width 0.4s;"></div>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="font-size:11px;color:#8a96b0;">Rp {{ number_format($terpakai, 0, ',', '.') }} terpakai</span>
                        <span style="font-size:11px;color:#8a96b0;">dari Rp {{ number_format($budget, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endif
                @endforeach
            @endif
        </div>

    </div>
</x-app-layout>