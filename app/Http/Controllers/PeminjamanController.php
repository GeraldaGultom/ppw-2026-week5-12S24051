<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class PeminjamanController extends Controller
{
    public function index(): View
    {
        return view('peminjamans.index', [
            'peminjamans'  => Peminjaman::with('buku')->latest()->paginate(10),
            'bukus'        => Buku::orderBy('judul')->get(['id', 'judul', 'stok']),
            'lastBorrower' => session('last_borrower'),
        ]);
    }

    public function pinjam(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'buku_id'       => ['required', 'integer', 'exists:bukus,id'],
            'nim_peminjam'  => ['required', 'string', 'min:6', 'max:15'],
            'nama_peminjam' => ['required', 'string', 'min:3', 'max:150'],
        ]);

        $nim = strtoupper(trim($validated['nim_peminjam']));

        try {
            $kode = DB::transaction(function () use ($validated, $nim) {
                // Kunci baris buku: request lain pada buku yang sama harus mengantre
                $buku = Buku::where('id', $validated['buku_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                // Cek stok SETELAH kunci diambil, jadi nilainya pasti terbaru
                if ($buku->stok < 1) {
                    throw new RuntimeException("Stok buku \"{$buku->judul}\" habis, peminjaman ditolak.");
                }

                $sudahPinjam = Peminjaman::where('buku_id', $buku->id)
                    ->where('nim_peminjam', $nim)
                    ->where('status', 'dipinjam')
                    ->exists();

                if ($sudahPinjam) {
                    throw new RuntimeException("NIM {$nim} masih meminjam buku ini dan belum mengembalikannya.");
                }

                // Dua penulisan atomik: kurangi stok + catat peminjaman
                $buku->decrement('stok');

                $peminjaman = $buku->peminjamans()->create([
                    'kode_pinjam'         => 'PJM-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5)),
                    'nim_peminjam'        => $nim,
                    'nama_peminjam'       => $validated['nama_peminjam'],
                    'tanggal_pinjam'      => now()->toDateString(),
                    'tanggal_jatuh_tempo' => now()->addDays(7)->toDateString(),
                    'status'              => 'dipinjam',
                ]);

                return $peminjaman->kode_pinjam;
            }, 3);

            session(['last_borrower' => $nim . ' - ' . $validated['nama_peminjam']]);

            return back()->with('success', "Peminjaman berhasil. Kode: {$kode}");
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (QueryException $e) {
            Log::error('[DB EXCEPTION][pinjam]: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan basis data. Peminjaman dibatalkan.');
        }
    }

    public function kembali(int $id): RedirectResponse
    {
        try {
            DB::transaction(function () use ($id) {
                $peminjaman = Peminjaman::where('id', $id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($peminjaman->status === 'dikembalikan') {
                    throw new RuntimeException('Buku ini sudah dikembalikan sebelumnya.');
                }

                $buku = Buku::where('id', $peminjaman->buku_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $buku->increment('stok');

                $peminjaman->update([
                    'status'          => 'dikembalikan',
                    'tanggal_kembali' => now()->toDateString(),
                ]);
            }, 3);

            return back()->with('success', 'Buku berhasil dikembalikan, stok diperbarui.');
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (QueryException $e) {
            Log::error('[DB EXCEPTION][kembali]: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan basis data. Pengembalian dibatalkan.');
        }
    }
}