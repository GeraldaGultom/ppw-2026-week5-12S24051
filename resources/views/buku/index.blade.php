<x-layout title="Daftar Buku">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Daftar Buku</h1>
        @auth
            <a href="{{ route('buku.create') }}" class="btn btn-del">+ Tambah Buku</a>
        @endauth
    </div>

    {{-- Kartu statistik (Fluent Query Builder) --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card text-center shadow-sm"><div class="card-body">
                <div class="text-muted small">Total Judul Buku</div>
                <div class="fs-3 fw-bold">{{ number_format($stats['total_judul']) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center shadow-sm"><div class="card-body">
                <div class="text-muted small">Total Stok Tersedia</div>
                <div class="fs-3 fw-bold">{{ number_format($stats['total_stok']) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center shadow-sm"><div class="card-body">
                <div class="text-muted small">Sedang Dipinjam</div>
                <div class="fs-3 fw-bold text-warning">{{ number_format($stats['sedang_dipinjam']) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center shadow-sm"><div class="card-body">
                <div class="text-muted small">Total Dikembalikan</div>
                <div class="fs-3 fw-bold text-success">{{ number_format($stats['total_dikembalikan']) }}</div>
            </div></div>
        </div>
    </div>

    {{-- Filter dinamis --}}
    <form method="GET" action="{{ route('buku.index') }}" class="row g-2 mb-2">
        <div class="col-md-5">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control" placeholder="Cari judul / penulis / ISBN">
        </div>
        <div class="col-md-4">
            <select name="kategori_id" class="form-select">
                <option value="">Semua kategori</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" @selected(request('kategori_id') == $k->id)>
                        {{ $k->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-del flex-fill">Cari</button>
            <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    @if($lastSearch)
        <p class="small text-muted">
            Pencarian terakhir (dari session):
            <a href="{{ route('buku.index', ['search' => $lastSearch]) }}">{{ $lastSearch }}</a>
        </p>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ISBN</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Kategori</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Dipinjam (x)</th>
                        @auth <th class="text-end">Aksi</th> @endauth
                    </tr>
                </thead>
                <tbody>
                    @forelse($bukus as $buku)
                        <tr>
                            <td class="text-nowrap">{{ $buku->isbn }}</td>
                            <td>
                                <a href="{{ route('buku.show', $buku) }}" class="text-decoration-none fw-semibold">
                                    {{ $buku->judul }}
                                </a>
                            </td>
                            <td>{{ $buku->penulis }}</td>
                            <td><span class="badge badge-del">{{ $buku->kategori->nama_kategori ?? '-' }}</span></td>
                            <td class="text-center">
                                @if($buku->stok > 0)
                                    {{ $buku->stok }}
                                @else
                                    <span class="badge text-bg-danger">Habis</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $buku->peminjamans_count }}</td>
                            @auth
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('buku.edit', $buku) }}" class="btn btn-sm btn-outline-secondary">Ubah</a>
                                    <form action="{{ route('buku.destroy', $buku) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus buku ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </td>
                            @endauth
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Tidak ada buku yang cocok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $bukus->links() }}</div>
</x-layout>