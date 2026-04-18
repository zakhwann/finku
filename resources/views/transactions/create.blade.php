<x-app-layout>
    <div class="page-header">
        <div>
            <div class="page-title">Tambah Transaksi</div>
            <div class="page-sub">Catat pemasukan atau pengeluaran baru</div>
        </div>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div style="max-width:520px;">
        <div class="card">
            <form method="POST" action="{{ route('transactions.store') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Tipe Transaksi</label>
                    <div style="display:flex;gap:10px;">
                        <label style="flex:1;cursor:pointer;">
                            <input type="radio" name="type" value="income"
                                   {{ old('type', 'expense') === 'income' ? 'checked' : '' }}
                                   style="display:none;" class="type-radio">
                            <div class="type-btn" data-type="income"
                                 style="text-align:center;padding:10px;border-radius:8px;border:2px solid #e2e8f0;font-size:13px;font-weight:500;transition:all 0.15s;">
                                💰 Pemasukan
                            </div>
                        </label>
                        <label style="flex:1;cursor:pointer;">
                            <input type="radio" name="type" value="expense"
                                   {{ old('type', 'expense') === 'expense' ? 'checked' : '' }}
                                   style="display:none;" class="type-radio">
                            <div class="type-btn" data-type="expense"
                                 style="text-align:center;padding:10px;border-radius:8px;border:2px solid #2e5fba;background:#eff4ff;font-size:13px;font-weight:500;transition:all 0.15s;">
                                💸 Pengeluaran
                            </div>
                        </label>
                    </div>
                    @error('type')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah (Rp)</label>
                    <input type="number" name="amount" class="form-control"
                           placeholder="cth: 50000" value="{{ old('amount') }}" min="1">
                    @error('amount')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-control">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }})
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="transaction_date" class="form-control"
                           value="{{ old('transaction_date', now()->format('Y-m-d')) }}">
                    @error('transaction_date')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan <span style="color:#8a96b0;font-weight:400;">(opsional)</span></label>
                    <input type="text" name="note" class="form-control"
                           placeholder="cth: Makan siang warteg" value="{{ old('note') }}">
                    @error('note')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    Simpan Transaksi
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