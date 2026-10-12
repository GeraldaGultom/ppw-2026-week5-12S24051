<div>
    <!-- Let all your things have their places; let each part of your business have its time. - Benjamin Franklin -->
<x-layout title="Daftar Buku">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Daftar Buku</h1>
        <a href="{{ route('buku.create') }}" class="btn btn-del">+ Tambah Buku</a>
    </div>

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
                        <th class="text-end">Aksi</th>
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
                            <td><span class="badge badge-del">{{ $buku->kategori->nama_kategori }}</span></td>
                            <td class="text-center">
                                @if($buku->stok > 0)
                                    {{ $buku->stok }}
                                @else
                                    <span class="badge text-bg-danger">Habis</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('buku.edit', $buku) }}" class="btn btn-sm btn-outline-secondary">Ubah</a>
                                <form action="{{ route('buku.destroy', $buku) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data buku.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $bukus->links() }}</div>
</x-layout>
</div>
