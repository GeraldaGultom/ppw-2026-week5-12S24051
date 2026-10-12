 <x-layout title="Detail Kategori">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Kategori: {{ $kategori->nama_kategori }}</h1>
        <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary">&larr; Kembali</a>
    </div>

    <p class="text-muted">Kode: <span class="badge badge-del">{{ $kategori->kode_kategori }}</span></p>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ISBN</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th class="text-center">Tahun</th>
                        <th class="text-center">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bukus as $buku)
                        <tr>
                            <td class="text-nowrap">{{ $buku->isbn }}</td>
                            <td>
                                <a href="{{ route('buku.show', $buku) }}" class="text-decoration-none">{{ $buku->judul }}</a>
                            </td>
                            <td>{{ $buku->penulis }}</td>
                            <td class="text-center">{{ $buku->tahun_terbit }}</td>
                            <td class="text-center">{{ $buku->stok }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada buku pada kategori ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $bukus->links() }}</div>

    <div class="mt-3">
        <a href="{{ route('kategori.edit', $kategori) }}" class="btn btn-del">Ubah Kategori</a>
    </div>
</x-layout>