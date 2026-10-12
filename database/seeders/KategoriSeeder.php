<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['kode_kategori' => 'PRG', 'nama_kategori' => 'Pemrograman'],
            ['kode_kategori' => 'BSD', 'nama_kategori' => 'Basis Data'],
            ['kode_kategori' => 'JRK', 'nama_kategori' => 'Jaringan Komputer'],
            ['kode_kategori' => 'AIM', 'nama_kategori' => 'Kecerdasan Buatan'],
            ['kode_kategori' => 'SIS', 'nama_kategori' => 'Sistem Informasi'],
            ['kode_kategori' => 'KEM', 'nama_kategori' => 'Keamanan Informasi'],
            ['kode_kategori' => 'MNJ', 'nama_kategori' => 'Manajemen Proyek'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}