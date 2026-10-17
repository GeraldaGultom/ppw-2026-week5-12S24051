<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Database\Seeder;

class BukuUjiSeeder extends Seeder
{
    public function run(): void
    {
        // Pakai kategori yang sudah ada; buat "Umum" hanya jika tabel kosong
        $kategori = Kategori::first()
            ?? Kategori::create(['kode_kategori' => 'UMUM', 'nama_kategori' => 'Umum']);

        $data = [
            ['isbn' => '9780000000010', 'judul' => 'Uji Stok Nol',  'stok' => 0],
            ['isbn' => '9780000000027', 'judul' => 'Uji Stok Satu', 'stok' => 1],
            // stok 4 karena 1 eksemplar sedang dipinjam (sampel peminjaman di bawah)
            ['isbn' => '9780000000034', 'judul' => 'Uji Sirkulasi', 'stok' => 4],
        ];

        $bukus = [];
        foreach ($data as $row) {
            $bukus[$row['isbn']] = Buku::updateOrCreate(
                ['isbn' => $row['isbn']],
                [
                    'judul'        => $row['judul'],
                    'penulis'      => 'Tim Uji SIPUS-Del',
                    'penerbit'     => 'Del Press',
                    'tahun_terbit' => 2025,
                    'kategori_id'  => $kategori->id,
                    'stok'         => $row['stok'],
                    'sinopsis'     => 'Data sampel untuk pengujian transaksi ACID.',
                ]
            );
        }

        $sirkulasi = $bukus['9780000000034'];

        // Sampel 1: masih dipinjam (konsisten dengan stok 4)
        Peminjaman::updateOrCreate(['kode_pinjam' => 'PJM-SEED-001'], [
            'buku_id'             => $sirkulasi->id,
            'nim_peminjam'        => '12S24001',
            'nama_peminjam'       => 'Mahasiswa Contoh A',
            'tanggal_pinjam'      => now()->subDays(2)->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(5)->toDateString(),
            'status'              => 'dipinjam',
        ]);

        // Sampel 2: sudah dikembalikan (tidak memengaruhi stok)
        Peminjaman::updateOrCreate(['kode_pinjam' => 'PJM-SEED-002'], [
            'buku_id'             => $sirkulasi->id,
            'nim_peminjam'        => '12S24002',
            'nama_peminjam'       => 'Mahasiswa Contoh B',
            'tanggal_pinjam'      => now()->subDays(10)->toDateString(),
            'tanggal_jatuh_tempo' => now()->subDays(3)->toDateString(),
            'tanggal_kembali'     => now()->subDays(4)->toDateString(),
            'status'              => 'dikembalikan',
        ]);
    }
}