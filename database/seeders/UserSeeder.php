<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Akun Admin
        User::create([
            'name' => 'Admin Utama KEN',
            'email' => 'admin@kenlogistics.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Buat Akun Kurir
        User::create([
            'name' => 'Budi (Kurir Lapangan)',
            'email' => 'kurir@kenlogistics.com',
            'password' => Hash::make('password123'),
            'role' => 'kurir',
        ]);
    }
}
