<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('buku.index'));

// Hanya tamu. throttle = maksimal 5 percobaan login per menit (anti brute force)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Publik: hanya melihat daftar
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');

// Terproteksi: semua rute yang mengubah data
Route::middleware('auth')->group(function () {
    Route::resource('buku', BukuController::class)->except(['index', 'show']);
    Route::resource('kategori', KategoriController::class)->except(['index', 'show']);

    Route::get('/peminjamans', [PeminjamanController::class, 'index'])->name('peminjamans.index');
    Route::post('/peminjamans', [PeminjamanController::class, 'pinjam'])->name('peminjamans.pinjam');
    Route::post('/peminjamans/{id}/kembali', [PeminjamanController::class, 'kembali'])
        ->whereNumber('id')
        ->name('peminjamans.kembali');
});

// show ditaruh paling akhir agar /buku/create tidak dianggap sebagai ID
Route::get('/buku/{buku}', [BukuController::class, 'show'])->name('buku.show');
Route::get('/kategori/{kategori}', [KategoriController::class, 'show'])->name('kategori.show');