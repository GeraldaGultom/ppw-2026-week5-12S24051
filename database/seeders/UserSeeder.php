<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // updateOrCreate: aman dijalankan berulang, akun tidak ganda
        User::updateOrCreate(
            ['email' => 'pustakawan@del.ac.id'],
            ['name' => 'Pustakawan Del', 'password' => Hash::make('password123')]
        );
    }
}