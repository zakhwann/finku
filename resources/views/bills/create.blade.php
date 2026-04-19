<x-app-layout>
    <div class="page-header">
        <div>
            <div class="page-title">Buat Sesi Split Bill</div>
            <div class="page-sub">Isi info dan tambahkan anggota</div>
        </div>
        <a href="{{ route('bills.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div style="max-width:560px;">
        <div class="card">
            <form method="POST" action="{{ route('bills.store') }}" id="createForm">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nama Sesi</label>
                    <input type="text" name="title" class="form-control"
                           placeholder="cth: Makan Siang di Warteg Pak Budi"
                           value="{{ old('title') }}" required>
                    @error('title')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Tempat <span style="color:#8a96b0;font-weight:400;">(opsional)</span></label>
                    <input type="text" name="place" class="form-control"
                           placeholder="cth: Warteg Pak Budi"
                           value="{{ old('place') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="date" class="form-control"
                           value="{{ old('date', now()->format('Y-m-d')) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Anggota <span style="color:#8a96b0;font-weight:400;">(minimal 2 orang)</span></label>
                    <div id="members-wrap" style="display:flex;flex-direction:column;gap:8px;">
                        <div class="member-row" style="display:flex;gap:8px;">
                            <input type="text" name="members[]" class="form-control" placeholder="Nama kamu" required>
                        </div>
                        <div class="member-row" style="display:flex;gap:8px;">
                            <input type="text" name="members[]" class="form-control" placeholder="Nama teman 1" required>
                            <button type="button" class="btn btn-danger remove-member" style="padding:8px 12px;flex-shrink:0;">✕</button>
                        </div>
                    </div>
                    <button type="button" id="addMember" class="btn btn-secondary" style="margin-top:10px;width:100%;justify-content:center;">
                        + Tambah Anggota
                    </button>
                    @error('members')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    Lanjut Input Pesanan →
                </button>
            </form>
        </div>
    </div>

    <script>
    let memberCount = 2;

    document.getElementById('addMember').addEventListener('click', function() {
        memberCount++;
        const wrap = document.getElementById('members-wrap');
        const div  = document.createElement('div');
        div.className = 'member-row';
        div.style.cssText = 'display:flex;gap:8px;';
        div.innerHTML = `
            <input type="text" name="members[]" class="form-control" placeholder="Nama teman ${memberCount - 1}" required>
            <button type="button" class="btn btn-danger remove-member" style="padding:8px 12px;flex-shrink:0;">✕</button>
        `;
        wrap.appendChild(div);
        attachRemove();
    });

    function attachRemove() {
        document.querySelectorAll('.remove-member').forEach(btn => {
            btn.onclick = function() {
                if (document.querySelectorAll('.member-row').length > 2) {
                    this.closest('.member-row').remove();
                }
            };
        });
    }

    attachRemove();
    </script>
</x-app-layout>