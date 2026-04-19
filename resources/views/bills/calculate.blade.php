<x-app-layout>
    <div class="page-header">
        <div>
            <div class="page-title">Hasil Perhitungan</div>
            <div class="page-sub">{{ $bill->title }} · {{ $bill->place }}</div>
        </div>
        <a href="{{ route('bills.edit', $bill) }}" class="btn btn-secondary">← Edit</a>
    </div>

    {{-- Summary --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
        <div class="card" style="background:#0f1a3a;border-color:#0f1a3a;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#5a7ab5;margin-bottom:8px;font-weight:600;">Total Bill</div>
            <div style="font-family:'Fraunces',serif;font-size:22px;color:#fff;">Rp {{ number_format($total, 0, ',', '.') }}</div>
        </div>
        <div class="card">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#8a96b0;margin-bottom:8px;font-weight:600;">Subtotal</div>
            <div style="font-family:'Fraunces',serif;font-size:22px;color:#1a1f2e;">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
        </div>
        <div class="card">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#8a96b0;margin-bottom:8px;font-weight:600;">Pajak ({{ $bill->tax_percent }}%)</div>
            <div style="font-family:'Fraunces',serif;font-size:22px;color:#1a1f2e;">Rp {{ number_format($tax, 0, ',', '.') }}</div>
        </div>
        <div class="card">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:#8a96b0;margin-bottom:8px;font-weight:600;">Mode Split</div>
            <div style="font-family:'Fraunces',serif;font-size:22px;color:#1a1f2e;">{{ $bill->split_mode === 'equal' ? 'Rata' : 'Custom' }}</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;">

        {{-- Hasil per orang --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Tagihan per Orang</div>
            </div>
            @foreach($results as $r)
            <div style="display:flex;align-items:center;gap:14px;padding:14px 0;border-bottom:1px solid #f0f4fc;">
                <div style="width:40px;height:40px;border-radius:50%;background:#eff4ff;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;color:#2e5fba;flex-shrink:0;">
                    {{ strtoupper(substr($r['name'], 0, 1)) }}
                </div>
                <div style="flex:1;">
                    <div style="font-size:14px;font-weight:600;">{{ $r['name'] }}</div>
                    @if($bill->split_mode === 'custom')
                    <div style="font-size:12px;color:#8a96b0;margin-top:2px;">
                        Pesanan: Rp {{ number_format($r['items_total'], 0, ',', '.') }}
                    </div>
                    @endif
                </div>
                <div style="text-align:right;">
                    <div style="font-family:'Fraunces',serif;font-size:20px;color:#2e5fba;font-weight:600;">
                        Rp {{ number_format($r['share_amount'], 0, ',', '.') }}
                    </div>
                    <div style="font-size:11px;color:#8a96b0;margin-top:2px;">harus bayar</div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Simpan ke hutang --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Simpan ke Hutang Piutang</div>
            </div>
            <p style="font-size:13px;color:#8a96b0;margin-bottom:16px;line-height:1.6;">
                Pilih siapa yang sudah bayar bill ini. Sisanya akan otomatis dicatat sebagai hutang ke orang tersebut.
            </p>

            <form method="POST" action="{{ route('bills.saveDebt', $bill) }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Yang Bayar Bill</label>
                    <select name="payer_id" class="form-control">
                        @foreach($results as $r)
                        <option value="{{ $r['id'] }}">{{ $r['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    Simpan ke Hutang Piutang →
                </button>
            </form>

            <div style="margin-top:12px;padding:12px;background:#f8faff;border-radius:8px;font-size:12px;color:#8a96b0;line-height:1.6;">
                💡 Setelah disimpan, kamu bisa track siapa yang sudah bayar balik di menu Hutang.
            </div>
        </div>

    </div>
</x-app-layout>