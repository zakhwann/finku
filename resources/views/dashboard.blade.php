<x-app-layout>
    <div class="page-header">
        <div>
            <div class="page-title">Selamat Datang, {{ Auth::user()->name }} </div>
            <div class="page-sub">{{ now()->translatedFormat('l, d F Y') }}</div>
        </div>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">
            + Tambah Transaksi
        </a>
    </div>

    {{-- Stat Cards --}}
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px;">
        <div class="card" style="background:#0f1a3a; border-color:#0f1a3a;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#5a7ab5;margin-bottom:10px;">Saldo Bulan Ini</div>
            <div style="font-family:'DM Serif Display',serif;font-size:28px;color:#fff;letter-spacing:-1px;">
                Rp {{ number_format($balance, 0, ',', '.') }}
            </div>
        </div>
        <div class="card">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#8a96b0;margin-bottom:10px;">Total Pemasukan</div>
            <div style="font-family:'DM Serif Display',serif;font-size:28px;color:#059669;letter-spacing:-1px;">
                Rp {{ number_format($totalIncome, 0, ',', '.') }}
            </div>
        </div>
        <div class="card">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#8a96b0;margin-bottom:10px;">Total Pengeluaran</div>
            <div style="font-family:'DM Serif Display',serif;font-size:28px;color:#dc2626;letter-spacing:-1px;">
                Rp {{ number_format($totalExpense, 0, ',', '.') }}
            </div>
        </div>
    </div>

    {{-- Bottom Grid --}}
    <div style="display:grid; grid-template-columns:1fr 340px; gap:16px;">

        {{-- Transaksi Terbaru --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Transaksi Terbaru</div>
                <a href="{{ route('transactions.index') }}" class="card-link">Lihat semua →</a>
            </div>
            @forelse($recentTransactions as $tx)
            <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid #f0f4fc;">
                <div style="width:36px;height:36px;border-radius:10px;background:#f0f4fc;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">
                    {{ $tx->type === 'income' ? '💰' : '💸' }}
                </div>
                <div style="flex:1;">
                    <div style="font-size:13px;font-weight:500;">{{ $tx->note ?? 'Tidak ada catatan' }}</div>
                    <div style="font-size:11px;color:#8a96b0;">{{ $tx->category->name }} · {{ $tx->transaction_date->format('d M') }}</div>
                </div>
                <div class="{{ $tx->type === 'income' ? 'amount-income' : 'amount-expense' }}">
                    {{ $tx->type === 'income' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                </div>
            </div>
            @empty
            <p style="color:#8a96b0;font-size:13px;text-align:center;padding:20px 0;">Belum ada transaksi.</p>
            @endforelse
        </div>

        {{-- Pengeluaran per Kategori --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Pengeluaran per Kategori</div>
            </div>
            @forelse($expenseByCategory as $catName => $total)
            @php $pct = $totalExpense > 0 ? round(($total / $totalExpense) * 100) : 0; @endphp
            <div style="margin-bottom:14px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                    <span style="font-size:12.5px;font-weight:500;">{{ $catName }}</span>
                    <span style="font-size:12px;color:#8a96b0;">{{ $pct }}%</span>
                </div>
                <div style="height:5px;background:#f0f4fc;border-radius:4px;overflow:hidden;">
                    <div style="height:100%;width:{{ $pct }}%;background:#2e5fba;border-radius:4px;"></div>
                </div>
            </div>
            @empty
            <p style="color:#8a96b0;font-size:13px;text-align:center;padding:20px 0;">Belum ada pengeluaran.</p>
            @endforelse
        </div>

    </div>
</x-app-layout>