<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class BukuController extends Controller
{
public function index(Request $request)
{
    // State sesi: simpan kata kunci pencarian terakhir
    if ($request->filled('search')) {
        session(['last_search' => $request->input('search')]);
    }

    $bukus = Buku::with('kategori')              // eager load: cegah N+1
        ->withCount('peminjamans')               // jumlah peminjaman via satu subquery
        ->when($request->filled('search'), function ($query) use ($request) {
            $s = $request->input('search');
            // Dikelompokkan agar OR tidak merusak filter kategori
            $query->where(function ($q) use ($s) {
                $q->where('judul', 'like', "%{$s}%")
                  ->orWhere('penulis', 'like', "%{$s}%")
                  ->orWhere('isbn', 'like', "%{$s}%");
            });
        })
        ->when($request->filled('kategori_id'), function ($query) use ($request) {
            $query->where('kategori_id', $request->input('kategori_id'));
        })
        ->orderBy('judul')
        ->paginate(10)
        ->withQueryString();

    // Statistik via Fluent Query Builder
    $stats = [
        'total_judul'        => DB::table('bukus')->count(),
        'total_stok'         => (int) DB::table('bukus')->sum('stok'),
        'sedang_dipinjam'    => DB::table('peminjamans')->where('status', 'dipinjam')->count(),
        'total_dikembalikan' => DB::table('peminjamans')->where('status', 'dikembalikan')->count(),
    ];

    return view('buku.index', [
        'bukus'      => $bukus,
        'stats'      => $stats,
        'kategoris'  => Kategori::orderBy('nama_kategori')->get(),
        'lastSearch' => session('last_search'),
    ]);
}
    public function show(Buku $buku)
    {
        $buku->load('kategori');
        return view('buku.show', compact('buku'));
    }

    public function edit(Buku $buku)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('buku.edit', compact('buku', 'kategoris'));
    }

    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate($this->rules($buku));

        $buku->update($validated);

        return redirect()->route('buku.index')
            ->with('success', 'Data buku berhasil diperbarui!');
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil dihapus!');
    }

    // Aturan validasi dipakai bersama oleh store() dan update()
    private function rules(?Buku $buku = null): array
    {
        return [
            'isbn'         => ['required', 'string', 'regex:/^[0-9X\-]{10,17}$/',
                               Rule::unique('bukus', 'isbn')->ignore($buku?->id)],
            'judul'        => ['required', 'string', 'min:5', 'max:200'],
            'penulis'      => ['required', 'string', 'max:150'],
            'penerbit'     => ['required', 'string', 'max:100'],
            'tahun_terbit' => ['required', 'integer', 'between:1900,' . date('Y')],
            'kategori_id'  => ['required', 'exists:kategoris,id'],
            'stok'         => ['required', 'integer', 'min:0'],
            'sinopsis'     => ['nullable', 'string'],
        ];
    }
}