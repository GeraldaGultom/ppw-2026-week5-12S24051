<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class BukuFactory extends Factory
{
    public function definition(): array
    {
        $kategori = Kategori::inRandomOrder()->first();
        $awalan = fake()->randomElement(['Dasar-Dasar', 'Pengantar', 'Panduan Praktis', 'Konsep dan Implementasi']);

        return [
            'isbn'         => fake()->unique()->isbn13(),
            'judul'        => $awalan . ' ' . $kategori->nama_kategori,
            'penulis'      => fake()->name(),
            'penerbit'     => fake()->randomElement([
                'Informatika Bandung', 'Andi Offset', 'Erlangga',
                'Gramedia Pustaka Utama', 'Elex Media Komputindo', 'Deepublish',
            ]),
            'tahun_terbit' => fake()->numberBetween(2005, 2025),
            'kategori_id'  => $kategori->id,
            'stok'         => fake()->numberBetween(0, 25),
            'sinopsis'     => "Buku ini membahas konsep {$kategori->nama_kategori} secara bertahap, "
                            . "dilengkapi studi kasus dan latihan soal untuk mahasiswa.",
        ];
    }
}