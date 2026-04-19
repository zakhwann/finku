<x-app-layout>
    <div class="page-header">
        <div>
            <div class="page-title">Catat Hutang Manual</div>
            <div class="page-sub">Tambah hutang atau piutang baru</div>
        </div>
        <a href="{{ route('debts.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div style="max-width:480px;">
        <div class="card">
            <form method="POST" action="{{ route('debts.store') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Tipe</label>
                    <div style="display:flex;gap:10px;">
                        <label style="flex:1;cursor:pointer;">
                            <input type="radio" name="type" value="lend"
                                   style="display:none;" class="type-radio" checked>
                            <div class="type-btn"
                                 style="text-align:center;padding:10px;border-radius:8px;border:2px solid #2e5fba;background:#eff4ff;font-size:13px;font-weight:500;transition:all 0.15s;">
                                💰 Piutang (orang hutang ke saya)
                            </div>
                        </label>
                        <label style="flex:1;cursor:pointer;">
                            <input type="radio" name="type" value="owe"
                                   style="display:none;" class="type-radio">
                            <div class="type-btn"
                                 style="text-align:center;padding:10px;border-radius:8px;border:2px solid #e2e8f0;background:#fff;font-size:13px;font-weight:500;transition:all 0.15s;">
                                💸 Hutang (saya yang hutang)
                            </div>
                        </label>
                    </div>
                    @error('type')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Orang</label>
                    <input type="text" name="person_name" class="form-control"
                           placeholder="cth: Budi, Ani, dll"
                           value="{{ old('person_name') }}" required>
                    @error('person_name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah (Rp)</label>
                    <input type="number" name="amount" class="form-control"
                           placeholder="cth: 50000" value="{{ old('amount') }}" min="1" required>
                    @error('amount')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan <span style="color:#8a96b0;font-weight:400;">(opsional)</span></label>
                    <input type="text" name="description" class="form-control"
                           placeholder="cth: Patungan makan siang"
                           value="{{ old('description') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Jatuh Tempo <span style="color:#8a96b0;font-weight:400;">(opsional)</span></label>
                    <input type="date" name="due_date" class="form-control"
                           value="{{ old('due_date') }}">
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    Simpan
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