<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BUAT AKUN ADMIN UTAMA
        User::create([
            'name'     => 'Admin Utama KEN',
            'email'    => 'admin@kenlogistics.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'status'   => 'Aktif',
        ]);
    }
}
