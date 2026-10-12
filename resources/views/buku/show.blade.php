<x-layout title="Detail Buku">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Detail Buku</h1>
        <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">&larr; Kembali</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="h5 mb-1">{{ $buku->judul }}</h2>
            <p class="text-muted">oleh {{ $buku->penulis }}</p>

            <dl class="row mb-0">
                <dt class="col-sm-3">ISBN</dt>
                <dd class="col-sm-9">{{ $buku->isbn }}</dd>

                <dt class="col-sm-3">Penerbit</dt>
                <dd class="col-sm-9">{{ $buku->penerbit }}</dd>

                <dt class="col-sm-3">Tahun Terbit</dt>
                <dd class="col-sm-9">{{ $buku->tahun_terbit }}</dd>

                <dt class="col-sm-3">Kategori</dt>
                <dd class="col-sm-9">
                    <a href="{{ route('kategori.show', $buku->kategori) }}">{{ $buku->kategori->nama_kategori }}</a>
                </dd>

                <dt class="col-sm-3">Stok</dt>
                <dd class="col-sm-9">{{ $buku->stok }}</dd>

                <dt class="col-sm-3">Sinopsis</dt>
                <dd class="col-sm-9">{{ $buku->sinopsis ?: '-' }}</dd>
            </dl>

            <hr>
            <a href="{{ route('buku.edit', $buku) }}" class="btn btn-del">Ubah</a>
        </div>
    </div>
</x-layout>