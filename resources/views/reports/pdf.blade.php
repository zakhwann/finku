<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Finku</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #1a1f2e; padding: 32px; }

        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; border-bottom: 2px solid #0f1a3a; padding-bottom: 16px; }
        .brand { font-size: 24px; font-weight: bold; color: #0f1a3a; }
        .brand-sub { font-size: 10px; color: #8a96b0; letter-spacing: 1px; text-transform: uppercase; margin-top: 2px; }
        .report-info { text-align: right; }
        .report-title { font-size: 14px; font-weight: bold; color: #0f1a3a; }
        .report-sub { font-size: 11px; color: #8a96b0; margin-top: 3px; }

        .summary { display: flex; gap: 16px; margin-bottom: 24px; }
        .summary-card { flex: 1; padding: 14px 16px; border-radius: 8px; border: 1px solid #e4e9f5; }
        .summary-card.dark { background: #0f1a3a; border-color: #0f1a3a; }
        .summary-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; color: #8a96b0; margin-bottom: 6px; font-weight: bold; }
        .summary-card.dark .summary-label { color: #5a7ab5; }
        .summary-value { font-size: 18px; font-weight: bold; color: #1a1f2e; }
        .summary-card.dark .summary-value { color: #fff; }
        .income { color: #059669; }
        .expense { color: #dc2626; }

        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th { background: #f0f4fc; text-align: left; padding: 8px 10px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; color: #8a96b0; }
        td { padding: 9px 10px; border-bottom: 1px solid #f0f4fc; font-size: 11px; }
        .text-right { text-align: right; }
        .badge { padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: bold; }
        .badge-income { background: #ecfdf5; color: #065f46; }
        .badge-expense { background: #fef2f2; color: #991b1b; }

        .footer { margin-top: 32px; text-align: center; font-size: 10px; color: #8a96b0; border-top: 1px solid #e4e9f5; padding-top: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="brand">Finku</div>
            <div class="brand-sub">Personal Finance</div>
        </div>
        <div class="report-info">
            <div class="report-title">Laporan Keuangan</div>
            <div class="report-sub">{{ $monthName }}</div>
            <div class="report-sub">{{ $user->name }}</div>
        </div>
    </div>

    <div class="summary">
        <div class="summary-card dark">
            <div class="summary-label">Saldo</div>
            <div class="summary-value">Rp {{ number_format($balance, 0, ',', '.') }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Pemasukan</div>
            <div class="summary-value income">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Pengeluaran</div>
            <div class="summary-value expense">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Catatan</th>
                <th>Kategori</th>
                <th>Tipe</th>
                <th class="text-right">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $tx)
            <tr>
                <td>{{ $tx->transaction_date->format('d/m/Y') }}</td>
                <td>{{ $tx->note ?? '-' }}</td>
                <td>{{ $tx->category->name }}</td>
                <td><span class="badge {{ $tx->type === 'income' ? 'badge-income' : 'badge-expense' }}">{{ $tx->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</span></td>
                <td class="text-right {{ $tx->type === 'income' ? 'income' : 'expense' }}">
                    {{ $tx->type === 'income' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;padding:20px;color:#8a96b0;">Belum ada transaksi.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Digenerate otomatis oleh Finku · {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>