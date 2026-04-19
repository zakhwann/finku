<x-app-layout>
    <div class="page-header">
        <div>
            <div class="page-title">{{ $bill->title }}</div>
            <div class="page-sub">{{ $bill->place }} · {{ $bill->date->format('d M Y') }}</div>
        </div>
        <a href="{{ route('bills.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('bills.update', $bill) }}" id="editForm">
            @csrf @method('PUT')

            {{-- Settings --}}
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid #f0f4fc;">
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Mode Split</label>
                    <select name="split_mode" class="form-control" id="splitMode">
                        <option value="equal">Rata (dibagi sama)</option>
                        <option value="custom">Custom (sesuai pesanan)</option>
                    </select>
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Pajak (%)</label>
                    <input type="number" name="tax_percent" class="form-control"
                           placeholder="0" min="0" max="100" step="0.1" value="10">
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Diskon (Rp)</label>
                    <input type="number" name="discount_amount" class="form-control"
                           placeholder="0" min="0" value="0">
                </div>
            </div>

            {{-- Items --}}
            <div class="card-header" style="margin-bottom:16px;">
                <div class="card-title">Input Pesanan</div>
                <button type="button" id="addItem" class="btn btn-primary" style="padding:6px 14px;font-size:12px;">+ Tambah Item</button>
            </div>

            {{-- Header tabel --}}
            <div style="display:grid;grid-template-columns:2fr 1.5fr 80px 140px 40px;gap:8px;margin-bottom:8px;">
                <div style="font-size:11px;font-weight:600;color:#8a96b0;text-transform:uppercase;letter-spacing:0.5px;">Nama Item</div>
                <div style="font-size:11px;font-weight:600;color:#8a96b0;text-transform:uppercase;letter-spacing:0.5px;">Harga (Rp)</div>
                <div style="font-size:11px;font-weight:600;color:#8a96b0;text-transform:uppercase;letter-spacing:0.5px;">Qty</div>
                <div style="font-size:11px;font-weight:600;color:#8a96b0;text-transform:uppercase;letter-spacing:0.5px;">Pesanan</div>
                <div></div>
            </div>

            <div id="items-wrap" style="display:flex;flex-direction:column;gap:8px;">
                <div class="item-row" style="display:grid;grid-template-columns:2fr 1.5fr 80px 140px 40px;gap:8px;align-items:center;">
                    <input type="text" name="items[0][name]" class="form-control" placeholder="cth: Nasi Goreng" required>
                    <input type="number" name="items[0][price]" class="form-control item-price" placeholder="15000" min="0" required>
                    <input type="number" name="items[0][qty]" class="form-control item-qty" placeholder="1" min="1" value="1" required>
                    <select name="items[0][member_id]" class="form-control member-select">
                        @foreach($bill->members as $member)
                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-danger remove-item" style="padding:8px;width:36px;justify-content:center;">✕</button>
                </div>
            </div>

            {{-- Summary --}}
            <div style="margin-top:20px;padding:16px;background:#f8faff;border-radius:10px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                    <span style="font-size:13px;color:#8a96b0;">Subtotal</span>
                    <span style="font-size:13px;font-weight:500;" id="subtotalDisplay">Rp 0</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                    <span style="font-size:13px;color:#8a96b0;">Pajak</span>
                    <span style="font-size:13px;font-weight:500;" id="taxDisplay">Rp 0</span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                    <span style="font-size:13px;color:#8a96b0;">Diskon</span>
                    <span style="font-size:13px;font-weight:500;color:#dc2626;" id="discountDisplay">- Rp 0</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-top:10px;border-top:1px solid #e4e9f5;">
                    <span style="font-size:14px;font-weight:600;">Total</span>
                    <span style="font-size:16px;font-weight:700;color:#2e5fba;font-family:'Fraunces',serif;" id="totalDisplay">Rp 0</span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:16px;">
                Hitung Sekarang →
            </button>
        </form>
    </div>

    <script>
    let itemIndex = 1;
    const members = @json($bill->members->map(fn($m) => ['id' => $m->id, 'name' => $m->name]));

    function getMemberOptions(selectedId = null) {
        return members.map(m =>
            `<option value="${m.id}" ${selectedId == m.id ? 'selected' : ''}>${m.name}</option>`
        ).join('');
    }

    document.getElementById('addItem').addEventListener('click', function() {
        const wrap = document.getElementById('items-wrap');
        const div  = document.createElement('div');
        div.className = 'item-row';
        div.style.cssText = 'display:grid;grid-template-columns:2fr 1.5fr 80px 140px 40px;gap:8px;align-items:center;';
        div.innerHTML = `
            <input type="text" name="items[${itemIndex}][name]" class="form-control" placeholder="Nama item" required>
            <input type="number" name="items[${itemIndex}][price]" class="form-control item-price" placeholder="0" min="0" required>
            <input type="number" name="items[${itemIndex}][qty]" class="form-control item-qty" placeholder="1" min="1" value="1" required>
            <select name="items[${itemIndex}][member_id]" class="form-control member-select">
                ${getMemberOptions()}
            </select>
            <button type="button" class="btn btn-danger remove-item" style="padding:8px;width:36px;justify-content:center;">✕</button>
        `;
        wrap.appendChild(div);
        itemIndex++;
        attachEvents();
        recalculate();
    });

    function attachEvents() {
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.onclick = function() {
                if (document.querySelectorAll('.item-row').length > 1) {
                    this.closest('.item-row').remove();
                    recalculate();
                }
            };
        });
        document.querySelectorAll('.item-price, .item-qty').forEach(input => {
            input.oninput = recalculate;
        });
    }

    function formatRp(num) {
        return 'Rp ' + Math.round(num).toLocaleString('id-ID');
    }

    function recalculate() {
        let subtotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            const qty   = parseInt(row.querySelector('.item-qty').value) || 1;
            subtotal += price * qty;
        });

        const taxPct   = parseFloat(document.querySelector('[name=tax_percent]').value) || 0;
        const discount = parseFloat(document.querySelector('[name=discount_amount]').value) || 0;
        const tax      = subtotal * (taxPct / 100);
        const total    = subtotal + tax - discount;

        document.getElementById('subtotalDisplay').textContent  = formatRp(subtotal);
        document.getElementById('taxDisplay').textContent       = formatRp(tax);
        document.getElementById('discountDisplay').textContent  = '- ' + formatRp(discount);
        document.getElementById('totalDisplay').textContent     = formatRp(total);
    }

    document.querySelector('[name=tax_percent]').oninput      = recalculate;
    document.querySelector('[name=discount_amount]').oninput  = recalculate;

    attachEvents();
    </script>
</x-app-layout>