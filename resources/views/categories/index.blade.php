<x-app-layout>
    <div class="page-header">
        <div>
            <div class="page-title">Kategori</div>
            <div class="page-sub">Kelola kategori pemasukan & pengeluaran</div>
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">+ Tambah Kategori</a>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Warna</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td style="color:#8a96b0;">{{ $loop->iteration }}</td>
                        <td style="font-weight:500;">{{ $cat->name }}</td>
                        <td>
                            <span class="badge {{ $cat->type === 'income' ? 'badge-income' : 'badge-expense' }}">
                                {{ $cat->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="width:18px;height:18px;border-radius:4px;background:{{ $cat->color }};"></div>
                                <span style="font-size:12px;color:#8a96b0;">{{ $cat->color }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="display:flex;gap:8px;">
                                <a href="{{ route('categories.edit', $cat) }}" class="btn btn-secondary" style="padding:5px 12px;font-size:12px;">Edit</a>
                                <form method="POST" action="{{ route('categories.destroy', $cat) }}"
                                      onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding:5px 12px;font-size:12px;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:#8a96b0;padding:30px;">
                            Belum ada kategori. <a href="{{ route('categories.create') }}" style="color:#2e5fba;">Tambah sekarang →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>