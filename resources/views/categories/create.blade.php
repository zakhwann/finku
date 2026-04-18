<x-app-layout>
    <div class="page-header">
        <div>
            <div class="page-title">Tambah Kategori</div>
            <div class="page-sub">Buat kategori baru untuk transaksimu</div>
        </div>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div style="max-width:480px;">
        <div class="card">
            <form method="POST" action="{{ route('categories.store') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="name" class="form-control"
                           placeholder="cth: Makan & Minum" value="{{ old('name') }}">
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tipe</label>
                    <select name="type" class="form-control">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="income"  {{ old('type') === 'income'  ? 'selected' : '' }}>Pemasukan</option>
                        <option value="expense" {{ old('type') === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                    @error('type')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Warna</label>
                    <div style="display:flex;align-items:center;gap:12px;">
                        <input type="color" name="color" value="{{ old('color', '#2e5fba') }}"
                               style="width:44px;height:44px;border:1px solid #e2e8f0;border-radius:8px;cursor:pointer;padding:2px;">
                        <span style="font-size:13px;color:#8a96b0;">Pilih warna untuk kategori ini</span>
                    </div>
                    @error('color')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    Simpan Kategori
                </button>
            </form>
        </div>
    </div>
</x-app-layout>