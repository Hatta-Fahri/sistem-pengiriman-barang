<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Sang Mandor: Memanggil seeder lain secara berurutan
        $this->call([
            UserSeeder::class,
            ShippingRateSeeder::class,
        ]);
    }
}
