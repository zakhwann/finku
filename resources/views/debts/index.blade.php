<x-app-layout>
    <div class="page-header">
        <div>
            <div class="page-title">Hutang & Piutang</div>
            <div class="page-sub">Catat dan pantau hutang piutangmu</div>
        </div>
        <a href="{{ route('debts.create') }}" class="btn btn-primary">+ Catat Manual</a>
    </div>

    {{-- Summary --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
        <div class="card" style="background:#0f1a3a;border-color:#0f1a3a;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#5a7ab5;margin-bottom:10px;font-weight:600;">Saldo Hutang Piutang</div>
            @php $netBalance = $totalLend - $totalOwe; @endphp
            <div style="font-family:'Fraunces',serif;font-size:26px;color:{{ $netBalance >= 0 ? '#6ee7b7' : '#fca5a5' }};letter-spacing:-1px;">
                {{ $netBalance >= 0 ? '+' : '' }}Rp {{ number_format($netBalance, 0, ',', '.') }}
            </div>
            <div style="font-size:12px;color:#3d5480;margin-top:6px;">
                {{ $netBalance >= 0 ? 'Lebih banyak piutang' : 'Lebih banyak hutang' }}
            </div>
        </div>
        <div class="card" style="border-top:3px solid #059669;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#8a96b0;margin-bottom:10px;font-weight:600;">Piutang Aktif</div>
            <div style="font-family:'Fraunces',serif;font-size:26px;color:#059669;letter-spacing:-1px;">
                Rp {{ number_format($totalLend, 0, ',', '.') }}
            </div>
            <div style="font-size:12px;color:#8a96b0;margin-top:4px;">{{ $lends->count() }} orang belum bayar</div>
        </div>
        <div class="card" style="border-top:3px solid #dc2626;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#8a96b0;margin-bottom:10px;font-weight:600;">Hutang Aktif</div>
            <div style="font-family:'Fraunces',serif;font-size:26px;color:#dc2626;letter-spacing:-1px;">
                Rp {{ number_format($totalOwe, 0, ',', '.') }}
            </div>
            <div style="font-size:12px;color:#8a96b0;margin-top:4px;">{{ $owes->count() }} hutang belum lunas</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">

        {{-- Piutang --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">💰 Piutang (orang hutang ke kamu)</div>
            </div>
            @forelse($lends as $debt)
            <div style="padding:12px 0;border-bottom:1px solid #f0f4fc;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;">
                    <div>
                        <div style="font-size:13px;font-weight:600;">{{ $debt->person_name }}</div>
                        <div style="font-size:11px;color:#8a96b0;margin-top:2px;">{{ $debt->description ?? '-' }}</div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:14px;font-weight:700;color:#059669;font-family:'Fraunces',serif;">
                            Rp {{ number_format($debt->remaining, 0, ',', '.') }}
                        </div>
                        @if($debt->status === 'partial')
                        <div style="font-size:10px;color:#f59e0b;">Sebagian lunas</div>
                        @endif
                    </div>
                </div>
                @if($debt->due_date)
                <div style="font-size:11px;color:{{ $debt->due_date->isPast() ? '#dc2626' : '#8a96b0' }};margin-bottom:8px;">
                    {{ $debt->due_date->isPast() ? '⚠️ Jatuh tempo' : '📅 Jatuh tempo' }}: {{ $debt->due_date->format('d M Y') }}
                </div>
                @endif
                <div style="display:flex;gap:6px;">
                    <form method="POST" action="{{ route('debts.markPaid', $debt) }}" style="display:flex;gap:6px;flex:1;">
                        @csrf
                        <input type="number" name="paid_amount" class="form-control"
                               placeholder="Jumlah dibayar" min="1"
                               max="{{ $debt->remaining }}"
                               style="font-size:12px;padding:6px 10px;">
                        <button type="submit" class="btn btn-primary" style="padding:6px 12px;font-size:12px;white-space:nowrap;">
                            Tandai Bayar
                        </button>
                    </form>
                    <form method="POST" action="{{ route('debts.destroy', $debt) }}"
                          onsubmit="return confirm('Hapus data ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding:6px 10px;font-size:12px;">✕</button>
                    </form>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:24px;color:#8a96b0;font-size:13px;">
                Tidak ada piutang aktif 🎉
            </div>
            @endforelse
        </div>

        {{-- Hutang --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">💸 Hutang (kamu yang hutang)</div>
            </div>
            @forelse($owes as $debt)
            <div style="padding:12px 0;border-bottom:1px solid #f0f4fc;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;">
                    <div>
                        <div style="font-size:13px;font-weight:600;">{{ $debt->person_name }}</div>
                        <div style="font-size:11px;color:#8a96b0;margin-top:2px;">{{ $debt->description ?? '-' }}</div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:14px;font-weight:700;color:#dc2626;font-family:'Fraunces',serif;">
                            Rp {{ number_format($debt->remaining, 0, ',', '.') }}
                        </div>
                        @if($debt->status === 'partial')
                        <div style="font-size:10px;color:#f59e0b;">Sebagian lunas</div>
                        @endif
                    </div>
                </div>
                @if($debt->due_date)
                <div style="font-size:11px;color:{{ $debt->due_date->isPast() ? '#dc2626' : '#8a96b0' }};margin-bottom:8px;">
                    {{ $debt->due_date->isPast() ? '⚠️ Jatuh tempo' : '📅 Jatuh tempo' }}: {{ $debt->due_date->format('d M Y') }}
                </div>
                @endif
                <div style="display:flex;gap:6px;">
                    <form method="POST" action="{{ route('debts.markPaid', $debt) }}" style="display:flex;gap:6px;flex:1;">
                        @csrf
                        <input type="number" name="paid_amount" class="form-control"
                               placeholder="Jumlah dibayar" min="1"
                               max="{{ $debt->remaining }}"
                               style="font-size:12px;padding:6px 10px;">
                        <button type="submit" class="btn btn-primary" style="padding:6px 12px;font-size:12px;white-space:nowrap;">
                            Tandai Bayar
                        </button>
                    </form>
                    <form method="POST" action="{{ route('debts.destroy', $debt) }}"
                          onsubmit="return confirm('Hapus data ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding:6px 10px;font-size:12px;">✕</button>
                    </form>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:24px;color:#8a96b0;font-size:13px;">
                Tidak ada hutang aktif 🎉
            </div>
            @endforelse
        </div>

    </div>

    {{-- Riwayat --}}
    @if($history->isNotEmpty())
    <div class="card">
        <div class="card-header">
            <div class="card-title">Riwayat yang Sudah Lunas</div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Tipe</th>
                        <th style="text-align:right;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $debt)
                    <tr>
                        <td style="font-weight:500;">{{ $debt->person_name }}</td>
                        <td style="color:#8a96b0;">{{ $debt->description ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $debt->type === 'lend' ? 'badge-income' : 'badge-expense' }}">
                                {{ $debt->type === 'lend' ? 'Piutang' : 'Hutang' }}
                            </span>
                        </td>
                        <td style="text-align:right;font-weight:600;color:#8a96b0;">
                            Rp {{ number_format($debt->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</x-app-layout>