<?php

return [
    'required' => ':attribute wajib diisi.',
    'string'   => ':attribute harus berupa teks.',
    'integer'  => ':attribute harus berupa bilangan bulat.',
    'email'    => ':attribute harus berupa alamat email yang valid.',
    'exists'   => ':attribute yang dipilih tidak valid.',
    'unique'   => ':attribute sudah terdaftar.',
    'regex'    => 'Format :attribute tidak valid.',
    'min' => [
        'string'  => ':attribute minimal :min karakter.',
        'numeric' => ':attribute minimal :min.',
    ],
    'max' => [
        'string'  => ':attribute maksimal :max karakter.',
        'numeric' => ':attribute maksimal :max.',
    ],
    'between' => [
        'numeric' => ':attribute harus di antara :min dan :max.',
    ],
    'attributes' => [
        'isbn'          => 'ISBN',
        'judul'         => 'Judul',
        'penulis'       => 'Penulis',
        'penerbit'      => 'Penerbit',
        'tahun_terbit'  => 'Tahun terbit',
        'kategori_id'   => 'Kategori',
        'stok'          => 'Stok',
        'sinopsis'      => 'Sinopsis',
        'kode_kategori' => 'Kode kategori',
        'nama_kategori' => 'Nama kategori',
    ],
];