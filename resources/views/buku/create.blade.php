<div>
    <!-- No surplus words or unnecessary actions. - Marcus Aurelius -->
<x-layout title="Tambah Buku">
    <h1 class="h4 mb-3">Tambah Buku</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('buku.store') }}" method="POST" novalidate>
                @csrf
                @include('buku._fields')

                <div class="mt-4">
                    <button type="submit" class="btn btn-del">Simpan</button>
                    <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-layout>
</div>
