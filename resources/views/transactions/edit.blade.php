<x-app-layout>
    <div class="page-header">
        <div>
            <div class="page-title">Edit Transaksi</div>
            <div class="page-sub">Ubah detail transaksi</div>
        </div>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div style="max-width:520px;">
        <div class="card">
            <form method="POST" action="{{ route('transactions.update', $transaction) }}">
                @csrf @method('PUT')

                <div class="form-group">
                    <label class="form-label">Tipe Transaksi</label>
                    <div style="display:flex;gap:10px;">
                        <label style="flex:1;cursor:pointer;">
                            <input type="radio" name="type" value="income"
                                   {{ $transaction->type === 'income' ? 'checked' : '' }}
                                   style="display:none;" class="type-radio">
                            <div class="type-btn"
                                 style="text-align:center;padding:10px;border-radius:8px;font-size:13px;font-weight:500;transition:all 0.15s;
                                 border: {{ $transaction->type === 'income' ? '2px solid #2e5fba' : '2px solid #e2e8f0' }};
                                 background: {{ $transaction->type === 'income' ? '#eff4ff' : '#fff' }};">
                                💰 Pemasukan
                            </div>
                        </label>
                        <label style="flex:1;cursor:pointer;">
                            <input type="radio" name="type" value="expense"
                                   {{ $transaction->type === 'expense' ? 'checked' : '' }}
                                   style="display:none;" class="type-radio">
                            <div class="type-btn"
                                 style="text-align:center;padding:10px;border-radius:8px;font-size:13px;font-weight:500;transition:all 0.15s;
                                 border: {{ $transaction->type === 'expense' ? '2px solid #2e5fba' : '2px solid #e2e8f0' }};
                                 background: {{ $transaction->type === 'expense' ? '#eff4ff' : '#fff' }};">
                                💸 Pengeluaran
                            </div>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah (Rp)</label>
                    <input type="number" name="amount" class="form-control"
                           value="{{ old('amount', $transaction->amount) }}" min="1">
                    @error('amount')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-control">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $transaction->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }})
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="transaction_date" class="form-control"
                           value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}">
                    @error('transaction_date')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan <span style="color:#8a96b0;font-weight:400;">(opsional)</span></label>
                    <input type="text" name="note" class="form-control"
                           value="{{ old('note', $transaction->note) }}">
                    @error('note')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    Update Transaksi
                </button>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.type-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.type-btn').forEach(btn => {
                    btn.style.border = '2px solid #e2e8f0';
                    btn.style.background = '#fff';
                });
                this.nextElementSibling.style.border = '2px solid #2e5fba';
                this.nextElementSibling.style.background = '#eff4ff';
            });
        });
    </script>
</x-app-layout>