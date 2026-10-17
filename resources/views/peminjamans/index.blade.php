<x-layout title="Sirkulasi Buku">
    <h1 class="h4 mb-3">Sirkulasi Buku</h1>

    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">
            @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
        </ul></div>
    @endif

    @if($lastBorrower)
        <p class="text-muted small">Peminjam terakhir (dari session): <strong>{{ $lastBorrower }}</strong></p>
    @endif

    <div class="card shadow-sm mb-4"><div class="card-body">
        <h6>Form Peminjaman</h6>
        <form method="POST" action="{{ route('peminjamans.pinjam') }}" class="row g-2">
            @csrf
            <div class="col-md-4">
                <select name="buku_id" class="form-select" required>
                    <option value="">-- Pilih buku --</option>
                    @foreach($bukus as $b)
                        <option value="{{ $b->id }}" @selected(old('buku_id') == $b->id)>
                            {{ $b->judul }} (stok: {{ $b->stok }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input name="nim_peminjam" value="{{ old('nim_peminjam') }}" class="form-control" placeholder="NIM" required>
            </div>
            <div class="col-md-4">
                <input name="nama_peminjam" value="{{ old('nama_peminjam') }}" class="form-control" placeholder="Nama peminjam" required>
            </div>
            <div class="col-md-2"><button class="btn btn-del w-100">Pinjam</button></div>
        </form>
    </div></div>

    <div class="card shadow-sm"><div class="card-body">
        <h6>Riwayat Peminjaman</h6>
        <div class="table-responsive">
        <table class="table table-sm align-middle">
            <thead><tr>
                <th>Kode</th><th>Buku</th><th>Peminjam</th><th>Pinjam</th>
                <th>Jatuh Tempo</th><th>Kembali</th><th>Status</th><th></th>
            </tr></thead>
            <tbody>
            @forelse($peminjamans as $p)
                <tr>
                    <td>{{ $p->kode_pinjam }}</td>
                    <td>{{ $p->buku->judul }}</td>
                    <td>{{ $p->nim_peminjam }}<br><small>{{ $p->nama_peminjam }}</small></td>
                    <td>{{ $p->tanggal_pinjam->format('d M Y') }}</td>
                    <td>{{ $p->tanggal_jatuh_tempo->format('d M Y') }}</td>
                    <td>{{ $p->tanggal_kembali?->format('d M Y') ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $p->status === 'dipinjam' ? 'text-bg-warning' : 'text-bg-success' }}">
                            {{ $p->status }}
                        </span>
                    </td>
                    <td>
                        @if($p->status === 'dipinjam')
                            <form method="POST" action="{{ route('peminjamans.kembali', $p->id) }}">
                                @csrf
                                <button class="btn btn-sm btn-outline-success">Kembalikan</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted">Belum ada peminjaman.</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
        {{ $peminjamans->links() }}
    </div></div>
</x-layout>