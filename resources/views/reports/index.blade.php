<x-app-layout>
    <div class="page-header">
        <div>
            <div class="page-title">Laporan Keuangan</div>
            <div class="page-sub">Ringkasan & export transaksi bulanan</div>
        </div>
    </div>

    {{-- Filter & Export --}}
    <div class="card" style="margin-bottom:20px;">
        <form method="GET" action="{{ route('reports.index') }}"
              style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
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

            <div style="margin-left:auto;display:flex;gap:8px;">
                <a href="{{ route('reports.excel', ['month'=>$month,'year'=>$year]) }}"
                   class="btn btn-secondary">
                    📊 Export Excel
                </a>
                <a href="{{ route('reports.pdf', ['month'=>$month,'year'=>$year]) }}"
                   class="btn btn-primary">
                    📄 Export PDF
                </a>
            </div>
        </form>
    </div>

    {{-- Summary --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
        <div class="card" style="background:#0f1a3a;border-color:#0f1a3a;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#5a7ab5;margin-bottom:10px;font-weight:600;">Saldo</div>
            <div style="font-family:'Fraunces',serif;font-size:26px;color:#fff;letter-spacing:-1px;">
                Rp {{ number_format($balance, 0, ',', '.') }}
            </div>
        </div>
        <div class="card" style="border-top:3px solid #059669;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#8a96b0;margin-bottom:10px;font-weight:600;">Total Pemasukan</div>
            <div style="font-family:'Fraunces',serif;font-size:26px;color:#059669;letter-spacing:-1px;">
                Rp {{ number_format($totalIncome, 0, ',', '.') }}
            </div>
        </div>
        <div class="card" style="border-top:3px solid #dc2626;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#8a96b0;margin-bottom:10px;font-weight:600;">Total Pengeluaran</div>
            <div style="font-family:'Fraunces',serif;font-size:26px;color:#dc2626;letter-spacing:-1px;">
                Rp {{ number_format($totalExpense, 0, ',', '.') }}
            </div>
        </div>
    </div>

    {{-- Tabel Transaksi --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                Transaksi — {{ Carbon\Carbon::create()->month($month)->translatedFormat('F') }} {{ $year }}
            </div>
            <div style="font-size:12px;color:#8a96b0;">{{ $transactions->count() }} transaksi</div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Catatan</th>
                        <th>Kategori</th>
                        <th>Tipe</th>
                        <th style="text-align:right;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                    <tr>
                        <td>{{ $tx->transaction_date->format('d M Y') }}</td>
                        <td>{{ $tx->note ?? '-' }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <div style="width:7px;height:7px;border-radius:50%;background:{{ $tx->category->color }}"></div>
                                {{ $tx->category->name }}
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $tx->type === 'income' ? 'badge-income' : 'badge-expense' }}">
                                {{ $tx->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                            </span>
                        </td>
                        <td style="text-align:right;" class="{{ $tx->type === 'income' ? 'amount-income' : 'amount-expense' }}">
                            {{ $tx->type === 'income' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:#8a96b0;padding:30px;">
                            Belum ada transaksi bulan ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>