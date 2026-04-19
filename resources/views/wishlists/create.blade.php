<x-app-layout>
    <div class="page-header">
        <div>
            <div class="page-title">Tambah Wishlist</div>
            <div class="page-sub">Set target barang impianmu</div>
        </div>
        <a href="{{ route('wishlists.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div style="max-width:560px;">
        <div class="card">
            <form method="POST" action="{{ route('wishlists.store') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="name" class="form-control"
                           placeholder="cth: Laptop ASUS ROG, iPhone 15, dll"
                           value="{{ old('name') }}" required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi <span style="color:#8a96b0;font-weight:400;">(opsional)</span></label>
                    <input type="text" name="description" class="form-control"
                           placeholder="cth: Untuk kuliah dan gaming"
                           value="{{ old('description') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Harga Target (Rp)</label>
                    <input type="number" name="target_price" class="form-control"
                           placeholder="cth: 8000000" min="1"
                           value="{{ old('target_price') }}" required>
                    @error('target_price')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Prioritas</label>
                        <select name="priority" class="form-control">
                            <option value="high"   {{ old('priority') === 'high'   ? 'selected' : '' }}>🔴 Tinggi</option>
                            <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>🟡 Sedang</option>
                            <option value="low"    {{ old('priority') === 'low'    ? 'selected' : '' }}>🟢 Rendah</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Target Tanggal <span style="color:#8a96b0;font-weight:400;">(opsional)</span></label>
                        <input type="date" name="target_date" class="form-control"
                               value="{{ old('target_date') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Link Produk <span style="color:#8a96b0;font-weight:400;">(opsional)</span></label>
                    <input type="url" name="product_url" class="form-control"
                           placeholder="https://tokopedia.com/..."
                           value="{{ old('product_url') }}">
                    @error('product_url')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Link Gambar <span style="color:#8a96b0;font-weight:400;">(opsional)</span></label>
                    <input type="url" name="image_url" class="form-control"
                           placeholder="https://..." value="{{ old('image_url') }}">
                    @error('image_url')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    Simpan Wishlist
                </button>
            </form>
        </div>
    </div>
</x-app-layout>