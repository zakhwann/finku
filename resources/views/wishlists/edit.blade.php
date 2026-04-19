<x-app-layout>
    <div class="page-header">
        <div>
            <div class="page-title">Edit Wishlist</div>
            <div class="page-sub">Update detail wishlist</div>
        </div>
        <a href="{{ route('wishlists.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div style="max-width:560px;">
        <div class="card">
            <form method="POST" action="{{ route('wishlists.update', $wishlist) }}">
                @csrf @method('PUT')

                <div class="form-group">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $wishlist->name) }}" required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi <span style="color:#8a96b0;font-weight:400;">(opsional)</span></label>
                    <input type="text" name="description" class="form-control"
                           value="{{ old('description', $wishlist->description) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Harga Target (Rp)</label>
                    <input type="number" name="target_price" class="form-control"
                           value="{{ old('target_price', $wishlist->target_price) }}" min="1" required>
                    @error('target_price')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Prioritas</label>
                        <select name="priority" class="form-control">
                            <option value="high"   {{ $wishlist->priority === 'high'   ? 'selected' : '' }}>🔴 Tinggi</option>
                            <option value="medium" {{ $wishlist->priority === 'medium' ? 'selected' : '' }}>🟡 Sedang</option>
                            <option value="low"    {{ $wishlist->priority === 'low'    ? 'selected' : '' }}>🟢 Rendah</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Target Tanggal</label>
                        <input type="date" name="target_date" class="form-control"
                               value="{{ old('target_date', $wishlist->target_date?->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Link Produk <span style="color:#8a96b0;font-weight:400;">(opsional)</span></label>
                    <input type="url" name="product_url" class="form-control"
                           value="{{ old('product_url', $wishlist->product_url) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Link Gambar <span style="color:#8a96b0;font-weight:400;">(opsional)</span></label>
                    <input type="url" name="image_url" class="form-control"
                           value="{{ old('image_url', $wishlist->image_url) }}">
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    Update Wishlist
                </button>
            </form>
        </div>
    </div>
</x-app-layout>