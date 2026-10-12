<x-layout title="Ubah Kategori">
    <h1 class="h4 mb-3">Ubah Kategori</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('kategori.update', $kategori) }}" method="POST" novalidate>
                @csrf
                @method('PUT')
                @include('kategori._fields')

                <div class="mt-4">
                    <button type="submit" class="btn btn-del">Perbarui</button>
                    <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-layout>