<x-app-layout>
    {{-- Budget Warnings --}}
@if($budgetWarnings->isNotEmpty())
<div style="margin-bottom:24px;">
    @foreach($budgetWarnings as $warn)
    <div style="display:flex;align-items:center;gap:14px;padding:14px 18px;border-radius:12px;margin-bottom:8px;
                background:{{ $warn['over'] ? '#fef2f2' : '#fffbeb' }};
                border:1px solid {{ $warn['over'] ? '#fecaca' : '#fde68a' }};">

        {{-- Icon --}}
        <div style="font-size:20px;flex-shrink:0;">
            {{ $warn['over'] ? '🚨' : '⚠️' }}
        </div>

        {{-- Info --}}
        <div style="flex:1;min-width:0;">
            <div style="font-size:13px;font-weight:600;color:{{ $warn['over'] ? '#991b1b' : '#92400e' }};">
                {{ $warn['over'] ? 'Budget Terlampaui!' : 'Budget Hampir Habis' }} —
                <span style="font-weight:700;">{{ $warn['name'] }}</span>
            </div>
            <div style="font-size:12px;color:{{ $warn['over'] ? '#b91c1c' : '#a16207' }};margin-top:2px;">
                Terpakai Rp {{ number_format($warn['terpakai'], 0, ',', '.') }}
                dari Rp {{ number_format($warn['budget'], 0, ',', '.') }}
                ({{ $warn['pct'] }}%)
            </div>
        </div>

        {{-- Progress bar --}}
        <div style="width:120px;flex-shrink:0;">
            <div style="height:6px;background:rgba(0,0,0,0.08);border-radius:4px;overflow:hidden;">
                <div style="height:100%;width:{{ min($warn['pct'], 100) }}%;border-radius:4px;
                            background:{{ $warn['over'] ? '#dc2626' : '#f59e0b' }};"></div>
            </div>
            <div style="font-size:11px;font-weight:700;color:{{ $warn['over'] ? '#dc2626' : '#f59e0b' }};margin-top:4px;text-align:right;">
                {{ $warn['pct'] }}%
            </div>
        </div>

        {{-- Link --}}
        <a href="{{ route('budgets.index') }}"
           style="font-size:12px;font-weight:600;color:{{ $warn['over'] ? '#dc2626' : '#d97706' }};
                  text-decoration:none;flex-shrink:0;white-space:nowrap;">
            Lihat Budget →
        </a>
    </div>
    @endforeach
</div>
@endif

    <div class="page-header">
        <div>
            <div class="page-title" style="font-family:'Sora',sans-serif;font-weight:300;letter-spacing:-0.5px;">Selamat Datang, {{ Auth::user()->name }}</div>
            <div class="page-sub">{{ now()->translatedFormat('l, d F Y') }}</div>
        </div>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">+ Tambah Transaksi</a>
    </div>

    {{-- Smart Recommendations --}}
@if($recommendations)
<div class="card" style="margin-bottom:24px;">
    <div class="card-header" style="margin-bottom:16px;">
        <div class="card-title">◉ Rekomendasi Cerdas</div>
        <div style="font-size:12px;color:#8a96b0;">Berdasarkan kondisi keuanganmu bulan ini</div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px;">
        @foreach($recommendations as $rec)
        <div style="padding:14px 16px;border-radius:10px;border:1px solid {{ $rec['border'] }};background:{{ $rec['bg'] }};">
            <div style="display:flex;align-items:flex-start;gap:10px;">
                <div style="width:28px;height:28px;border-radius:50%;background:{{ $rec['color'] }};display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0;">
                    {{ $rec['icon'] }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:13px;font-weight:600;color:{{ $rec['color'] }};margin-bottom:4px;">
                        {{ $rec['title'] }}
                    </div>
                    <div style="font-size:12px;color:#6b7280;line-height:1.5;">
                        {{ $rec['message'] }}
                    </div>
                    @if($rec['action'])
                    <a href="{{ $rec['action']['url'] }}"
                       style="display:inline-block;margin-top:8px;font-size:12px;font-weight:600;color:{{ $rec['color'] }};text-decoration:none;">
                        {{ $rec['action']['label'] }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

    {{-- Stat Cards --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
        <div class="card" style="background:#0f1a3a;border-color:#0f1a3a;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#5a7ab5;margin-bottom:10px;font-weight:600;">Saldo Bulan Ini</div>
            <div style="font-family:'Playfair Display',serif;font-size:28px;color:#fff;letter-spacing:-1px;margin-bottom:8px;">
                Rp {{ number_format($balance, 0, ',', '.') }}
            </div>
            <div style="font-size:12px;color:#3d5480;">Pemasukan - Pengeluaran</div>
        </div>
        <div class="card" style="border-top:3px solid #059669;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#8a96b0;margin-bottom:10px;font-weight:600;">Total Pemasukan</div>
            <div style="font-family:'Playfair Display',serif;font-size:28px;color:#059669;letter-spacing:-1px;margin-bottom:8px;">
                Rp {{ number_format($totalIncome, 0, ',', '.') }}
            </div>
            <div style="font-size:12px;color:#8a96b0;">Bulan {{ now()->translatedFormat('F Y') }}</div>
        </div>
        <div class="card" style="border-top:3px solid #dc2626;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#8a96b0;margin-bottom:10px;font-weight:600;">Total Pengeluaran</div>
            <div style="font-family:'Playfair Display',serif;font-size:28px;color:#dc2626;letter-spacing:-1px;margin-bottom:8px;">
                Rp {{ number_format($totalExpense, 0, ',', '.') }}
            </div>
            <div style="font-size:12px;color:#8a96b0;">Bulan {{ now()->translatedFormat('F Y') }}</div>
        </div>
    </div>

    {{-- Debt Summary Widget --}}
@if($debtSummary['totalOwe'] > 0 || $debtSummary['totalLend'] > 0)
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
    <a href="{{ route('debts.index') }}" style="text-decoration:none;">
        <div class="card" style="border-left:4px solid #059669;border-radius:0 14px 14px 0;display:flex;align-items:center;gap:14px;">
            <div style="width:36px;height:36px;border-radius:8px;background:#e8f5ee;display:flex;align-items:center;justify-content:center;font-size:16px;color:#059669;font-weight:700;flex-shrink:0;">↑</div>
            <div>
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#8a96b0;font-weight:600;">Piutang Aktif</div>
                <div style="font-family:'Fraunces',serif;font-size:20px;color:#059669;">Rp {{ number_format($debtSummary['totalLend'], 0, ',', '.') }}</div>
            </div>
        </div>
    </a>
    <a href="{{ route('debts.index') }}" style="text-decoration:none;">
        <div class="card" style="border-left:4px solid #dc2626;border-radius:0 14px 14px 0;display:flex;align-items:center;gap:14px;">
            <div style="width:36px;height:36px;border-radius:8px;background:#fee2e2;display:flex;align-items:center;justify-content:center;font-size:16px;color:#dc2626;font-weight:700;flex-shrink:0;">↓</div>
            <div>
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#8a96b0;font-weight:600;">Hutang Aktif</div>
                <div style="font-family:'Fraunces',serif;font-size:20px;color:#dc2626;">Rp {{ number_format($debtSummary['totalOwe'], 0, ',', '.') }}</div>
            </div>
        </div>
    </a>
</div>
@endif

    {{-- Charts Row --}}
    <div style="display:grid;grid-template-columns:1fr 340px;gap:16px;margin-bottom:24px;">

        {{-- Bar Chart --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Arus Kas 6 Bulan Terakhir</div>
                <div style="display:flex;gap:16px;">
                    <span style="display:flex;align-items:center;gap:6px;font-size:12px;color:#8a96b0;">
                        <span style="width:10px;height:10px;border-radius:2px;background:#2e5fba;display:inline-block;"></span> Pemasukan
                    </span>
                    <span style="display:flex;align-items:center;gap:6px;font-size:12px;color:#8a96b0;">
                        <span style="width:10px;height:10px;border-radius:2px;background:#e8edf8;display:inline-block;border:1px solid #c8d4f0;"></span> Pengeluaran
                    </span>
                </div>
            </div>
            <canvas id="barChart" height="110"></canvas>
        </div>

        {{-- Doughnut Chart --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Pengeluaran per Kategori</div>
            </div>
            @if($expenseByCategory->isEmpty())
                <div style="text-align:center;padding:40px 0;color:#8a96b0;font-size:13px;">Belum ada pengeluaran bulan ini.</div>
            @else
                <canvas id="doughnutChart" height="180"></canvas>
                <div style="margin-top:16px;display:flex;flex-direction:column;gap:8px;" id="donut-legend"></div>
            @endif
        </div>
    </div>

    {{-- Bottom Row --}}
    <div style="display:grid;grid-template-columns:1fr 340px;gap:16px;">

        {{-- Transaksi Terbaru --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Transaksi Terbaru</div>
                <a href="{{ route('transactions.index') }}" class="card-link">Lihat semua →</a>
            </div>
            @forelse($recentTransactions as $tx)
            <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid #f0f4fc;">
                <div style="width:38px;height:38px;border-radius:10px;background:#f0f4fc;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">
                    {{ $tx->type === 'income' ? '💰' : '💸' }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $tx->note ?? 'Tidak ada catatan' }}
                    </div>
                    <div style="font-size:11px;color:#8a96b0;margin-top:2px;">
                        {{ $tx->category->name }} · {{ $tx->transaction_date->format('d M') }}
                    </div>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                    <div class="{{ $tx->type === 'income' ? 'amount-income' : 'amount-expense' }}" style="font-size:13px;">
                        {{ $tx->type === 'income' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:30px 0;color:#8a96b0;font-size:13px;">
                Belum ada transaksi. <a href="{{ route('transactions.create') }}" style="color:#2e5fba;">Tambah sekarang →</a>
            </div>
            @endforelse
        </div>

        {{-- Quick Stats --}}
        {{-- Quick Stats + Budget Widget --}}
<div style="display:flex;flex-direction:column;gap:16px;">

    <div class="card" style="text-align:center;">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#8a96b0;font-weight:600;margin-bottom:8px;">Rata-rata Pengeluaran/Hari</div>
        <div style="font-family:'Fraunces',serif;font-size:24px;color:#1a1f2e;letter-spacing:-0.5px;">
            Rp {{ number_format($totalExpense / max(now()->day, 1), 0, ',', '.') }}
        </div>
    </div>

    <div class="card" style="text-align:center;">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#8a96b0;font-weight:600;margin-bottom:8px;">Rasio Tabungan</div>
        @php $ratio = $totalIncome > 0 ? round((($totalIncome - $totalExpense) / $totalIncome) * 100) : 0; @endphp
        <div style="height:6px;background:#f0f4fc;border-radius:4px;overflow:hidden;margin-bottom:8px;">
            <div style="height:100%;width:{{ max(0, min(100, $ratio)) }}%;background:{{ $ratio >= 20 ? '#059669' : ($ratio >= 0 ? '#f59e0b' : '#dc2626') }};border-radius:4px;"></div>
        </div>
        <div style="font-size:13px;font-weight:600;color:{{ $ratio >= 20 ? '#059669' : ($ratio >= 0 ? '#f59e0b' : '#dc2626') }};">
            {{ $ratio }}% tersimpan
        </div>
    </div>

    {{-- Budget Widget --}}
    <div class="card">
        <div class="card-header" style="margin-bottom:14px;">
            <div class="card-title">Budget Bulan Ini</div>
            <a href="{{ route('budgets.index') }}" class="card-link">Atur →</a>
        </div>

        @if($budgetWidget->isEmpty())
            <div style="text-align:center;padding:16px 0;color:#8a96b0;font-size:12px;">
                Belum ada budget.<br>
                <a href="{{ route('budgets.index') }}" style="color:#2e5fba;font-weight:500;">Set budget sekarang →</a>
            </div>
        @else
            @foreach($budgetWidget as $b)
            <div style="margin-bottom:14px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px;">
                    <div style="display:flex;align-items:center;gap:6px;">
                        <div style="width:7px;height:7px;border-radius:50%;background:{{ $b['color'] }};flex-shrink:0;"></div>
                        <span style="font-size:12px;font-weight:500;color:#374151;">{{ $b['name'] }}</span>
                    </div>
                    <span style="font-size:11px;font-weight:600;color:{{ $b['over'] ? '#dc2626' : ($b['pct'] >= 80 ? '#f59e0b' : '#8a96b0') }};">
                        {{ $b['pct'] }}%{{ $b['over'] ? ' ⚠️' : '' }}
                    </span>
                </div>
                <div style="height:5px;background:#f0f4fc;border-radius:4px;overflow:hidden;">
                    <div style="height:100%;width:{{ $b['pct'] }}%;border-radius:4px;background:{{ $b['over'] ? '#dc2626' : ($b['pct'] >= 80 ? '#f59e0b' : '#2e5fba') }};transition:width 0.3s;"></div>
                </div>
                <div style="font-size:10.5px;color:#a0aec0;margin-top:3px;">
                    Rp {{ number_format($b['terpakai'], 0, ',', '.') }} / Rp {{ number_format($b['budget'], 0, ',', '.') }}
                </div>
            </div>
            @endforeach
        @endif
    </div>

</div>

    </div>

    {{-- Chart Scripts --}}
    <script>
        // Data dari Laravel
        const chartData = @json($chartData);
        const expenseData = @json($expenseByCategory);

        // Warna palette navy
        const colors = ['#2e5fba','#4a7fd4','#6a9de8','#8fb8f0','#1a3d7a','#0f1a3a'];

        // Bar Chart
        const barCtx = document.getElementById('barChart');
        if (barCtx) {
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: chartData.map(d => d.month),
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: chartData.map(d => d.income),
                            backgroundColor: '#2e5fba',
                            borderRadius: 6,
                            borderSkipped: false,
                        },
                        {
                            label: 'Pengeluaran',
                            data: chartData.map(d => d.expense),
                            backgroundColor: '#e8edf8',
                            borderRadius: 6,
                            borderSkipped: false,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Plus Jakarta Sans', size: 12 }, color: '#8a96b0' }
                        },
                        y: {
                            grid: { color: '#f0f4fc' },
                            ticks: {
                                font: { family: 'Plus Jakarta Sans', size: 11 },
                                color: '#8a96b0',
                                callback: val => 'Rp ' + (val >= 1000000 ? (val/1000000).toFixed(1)+'jt' : (val/1000).toFixed(0)+'rb')
                            }
                        }
                    }
                }
            });
        }

        // Doughnut Chart
        const donutCtx = document.getElementById('doughnutChart');
        if (donutCtx && Object.keys(expenseData).length > 0) {
            const labels = Object.keys(expenseData);
            const values = Object.values(expenseData);
            const total  = values.reduce((a, b) => a + b, 0);

            new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: colors.slice(0, labels.length),
                        borderWidth: 0,
                        hoverOffset: 6,
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '68%',
                    plugins: { legend: { display: false } }
                }
            });

            // Custom legend
            const legend = document.getElementById('donut-legend');
            labels.forEach((label, i) => {
                const pct = Math.round((values[i] / total) * 100);
                legend.innerHTML += `
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:8px;height:8px;border-radius:50%;background:${colors[i]};flex-shrink:0;"></div>
                            <span style="font-size:12px;color:#374151;">${label}</span>
                        </div>
                        <span style="font-size:12px;color:#8a96b0;font-weight:500;">${pct}%</span>
                    </div>`;
            });
        }
    </script>
</x-app-layout>