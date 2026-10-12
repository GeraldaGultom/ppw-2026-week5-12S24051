<x-layout title="Ubah Buku">
    <h1 class="h4 mb-3">Ubah Buku</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('buku.update', $buku) }}" method="POST" novalidate>
                @csrf
                @method('PUT')
                @include('buku._fields')

                <div class="mt-4">
                    <button type="submit" class="btn btn-del">Perbarui</button>
                    <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-layout>