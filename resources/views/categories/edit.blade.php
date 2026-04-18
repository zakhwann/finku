<x-app-layout>
    <div class="page-header">
        <div>
            <div class="page-title">Edit Kategori</div>
            <div class="page-sub">Ubah detail kategori</div>
        </div>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div style="max-width:480px;">
        <div class="card">
            <form method="POST" action="{{ route('categories.update', $category) }}">
                @csrf @method('PUT')

                <div class="form-group">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $category->name) }}">
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tipe</label>
                    <select name="type" class="form-control">
                        <option value="income"  {{ $category->type === 'income'  ? 'selected' : '' }}>Pemasukan</option>
                        <option value="expense" {{ $category->type === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                    @error('type')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Warna</label>
                    <div style="display:flex;align-items:center;gap:12px;">
                        <input type="color" name="color" value="{{ old('color', $category->color) }}"
                               style="width:44px;height:44px;border:1px solid #e2e8f0;border-radius:8px;cursor:pointer;padding:2px;">
                        <span style="font-size:13px;color:#8a96b0;">Pilih warna untuk kategori ini</span>
                    </div>
                    @error('color')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    Update Kategori
                </button>
            </form>
        </div>
    </div>
</x-app-layout>