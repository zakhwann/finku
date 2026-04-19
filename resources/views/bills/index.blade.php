<x-app-layout>
    <div class="page-header">
        <div>
            <div class="page-title">Split Bill</div>
            <div class="page-sub">Hitung tagihan bersama dengan adil</div>
        </div>
        <a href="{{ route('bills.create') }}" class="btn btn-primary">+ Buat Sesi Baru</a>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Tempat</th>
                        <th>Tanggal</th>
                        <th>Anggota</th>
                        <th>Mode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                    <tr>
                        <td style="font-weight:500;">{{ $session->title }}</td>
                        <td style="color:#8a96b0;">{{ $session->place ?? '-' }}</td>
                        <td>{{ $session->date->format('d M Y') }}</td>
                        <td>{{ $session->members->count() }} orang</td>
                        <td>
                            <span class="badge" style="background:#eff4ff;color:#2e5fba;">
                                {{ $session->split_mode === 'equal' ? 'Rata' : 'Custom' }}
                            </span>
                        </td>
                        <td>
                            @if($session->status === 'draft')
                                <span class="badge" style="background:#fef3c7;color:#d97706;">Draft</span>
                            @elseif($session->status === 'calculated')
                                <span class="badge" style="background:#eff4ff;color:#2e5fba;">Dihitung</span>
                            @else
                                <span class="badge badge-income">Tersimpan</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:8px;">
                                @if($session->status === 'draft')
                                    <a href="{{ route('bills.edit', $session) }}" class="btn btn-secondary" style="padding:5px 12px;font-size:12px;">Input Item</a>
                                @else
                                    <a href="{{ route('bills.calculate', $session) }}" class="btn btn-secondary" style="padding:5px 12px;font-size:12px;">Lihat</a>
                                @endif
                                <form method="POST" action="{{ route('bills.destroy', $session) }}"
                                      onsubmit="return confirm('Hapus sesi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding:5px 12px;font-size:12px;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;color:#8a96b0;padding:30px;">
                            Belum ada sesi. <a href="{{ route('bills.create') }}" style="color:#2e5fba;">Buat sekarang →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>