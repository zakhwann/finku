<x-app-layout>
    <div class="page-header">
        <div>
            <div class="page-title">Transaksi</div>
            <div class="page-sub">Riwayat semua transaksimu</div>
        </div>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">+ Tambah Transaksi</a>
    </div>

    {{-- Filter --}}
    <div class="card" style="margin-bottom:16px;">
        <form method="GET" action="{{ route('transactions.index') }}"
              style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
            <div>
                <label class="form-label">Tipe</label>
                <select name="type" class="form-control" style="width:150px;">
                    <option value="">Semua</option>
                    <option value="income"  {{ request('type') === 'income'  ? 'selected' : '' }}>Pemasukan</option>
                    <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>
            <div>
                <label class="form-label">Kategori</label>
                <select name="category_id" class="form-control" style="width:180px;">
                    <option value="">Semua</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Bulan</label>
                <input type="month" name="month" class="form-control"
                       value="{{ request('month') }}" style="width:160px;">
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Catatan</th>
                        <th>Kategori</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                    <tr>
                        <td style="color:#8a96b0;">{{ $loop->iteration }}</td>
                        <td>{{ $tx->transaction_date->format('d M Y') }}</td>
                        <td>{{ $tx->note ?? '-' }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <div style="width:8px;height:8px;border-radius:50%;background:{{ $tx->category->color }}"></div>
                                {{ $tx->category->name }}
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $tx->type === 'income' ? 'badge-income' : 'badge-expense' }}">
                                {{ $tx->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                            </span>
                        </td>
                        <td class="{{ $tx->type === 'income' ? 'amount-income' : 'amount-expense' }}">
                            {{ $tx->type === 'income' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                        </td>
                        <td>
                            <div style="display:flex;gap:8px;">
                                <a href="{{ route('transactions.edit', $tx) }}" class="btn btn-secondary" style="padding:5px 12px;font-size:12px;">Edit</a>
                                <form method="POST" action="{{ route('transactions.destroy', $tx) }}"
                                      onsubmit="return confirm('Hapus transaksi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding:5px 12px;font-size:12px;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;color:#8a96b0;padding:30px;">
                            Belum ada transaksi. <a href="{{ route('transactions.create') }}" style="color:#2e5fba;">Tambah sekarang →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
        <div style="margin-top:16px;">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</x-app-layout>