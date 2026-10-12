<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan penting: kategori harus ada dulu sebelum buku dibuat
        $this->call([
            KategoriSeeder::class,
            BukuSeeder::class,
        ]);
    }
}