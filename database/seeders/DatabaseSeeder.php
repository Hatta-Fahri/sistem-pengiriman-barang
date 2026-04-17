<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\ShippingRate;
use App\Models\Shipment;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. BUAT AKUN ADMIN
        User::create([
            'name'     => 'Admin Utama KEN',
            'email'    => 'admin@kenlogistics.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'status'   => 'Aktif',
        ]);

        // 2. BUAT AKUN KURIR
        User::create([
            'name'     => 'Budi (Kurir Lapangan)',
            'email'    => 'budi@kenlogistics.com',
            'password' => Hash::make('password'),
            'role'     => 'kurir',
            'status'   => 'Aktif',
        ]);

        User::create([
            'name'     => 'Anton (Kurir Lapangan)',
            'email'    => 'anton@kenlogistics.com',
            'password' => Hash::make('password'),
            'role'     => 'kurir',
            'status'   => 'Aktif',
        ]);

        // 3. BUAT ARMADA KENDARAAN
        Vehicle::create([
            'license_plate' => 'BK 1234 WP',
            'type'          => 'Mobil Box',
            'capacity'      => 1000,
            'status'        => 'Tersedia',
        ]);

        Vehicle::create([
            'license_plate' => 'B 9999 XYZ',
            'type'          => 'Truk Engkel',
            'capacity'      => 2500,
            'status'        => 'Tersedia',
        ]);

        // 4. BUAT RUTE & TARIF (Sesuai Struktur MySQL Terbaru)
        ShippingRate::create([
            'origin_city'           => 'Medan',
            'destination_city'      => 'Pematangsiantar',
            'jalur_pengiriman'      => 'Medan - Pematangsiantar',
            'estimated_distance_km' => 128.50,
            'cost_per_kg'           => 15000.00,
        ]);

        ShippingRate::create([
            'origin_city'           => 'Medan',
            'destination_city'      => 'Binjai',
            'jalur_pengiriman'      => 'Medan - Binjai',
            'estimated_distance_km' => 22.00,
            'cost_per_kg'           => 10000.00,
        ]);

        // 5. BUAT 10 RESI BARU
        $penerimaSiantar = ['Kiyo', 'Ahmad', 'Siti', 'Budianto', 'Clara'];
        $penerimaBinjai  = ['Joko', 'Rina', 'Tono', 'Maya', 'Zaki'];

        // Resi tujuan Pematangsiantar
        foreach ($penerimaSiantar as $index => $nama) {
            $weight = rand(5, 50);

            Shipment::create([
                'tracking_number'  => 'KEN-20260417-SNT0' . ($index + 1),
                'sender_name'      => 'Gudang Pusat Medan',
                'sender_phone'     => '08111222333',
                'sender_address'   => 'Jl. Gatot Subroto No. 123, Medan',
                'receiver_name'    => $nama,
                'receiver_phone'   => '0822' . rand(10000000, 99999999),
                'receiver_address' => 'Jl. Merdeka No. ' . rand(1, 100) . ', Pematangsiantar',
                'origin_city'      => 'Medan',
                'destination_city' => 'Pematangsiantar',
                'jalur_pengiriman' => 'Medan - Pematangsiantar',
                'item_description' => 'Paket Pakaian / Alat Tulis. Tolong titip di teras kalau kosong.',
                'jumlah_koli'      => rand(1, 3),
                'weight'           => $weight,
                'shipping_cost'    => $weight * 15000,
                'current_status'   => 'Diproses',
            ]);
        }

        // Resi tujuan Binjai
        foreach ($penerimaBinjai as $index => $nama) {
            $weight = rand(2, 20);

            Shipment::create([
                'tracking_number'  => 'KEN-20260417-BNJ0' . ($index + 1),
                'sender_name'      => 'Toko Elektronik Makmur',
                'sender_phone'     => '08333444555',
                'sender_address'   => 'Jl. Asia No. 45, Medan',
                'receiver_name'    => $nama,
                'receiver_phone'   => '0813' . rand(10000000, 99999999),
                'receiver_address' => 'Jl. Sudirman No. ' . rand(1, 100) . ', Binjai',
                'origin_city'      => 'Medan',
                'destination_city' => 'Binjai',
                'jalur_pengiriman' => 'Medan - Binjai',
                'item_description' => 'Barang Elektronik Pecah Belah, hati-hati.',
                'jumlah_koli'      => 1,
                'weight'           => $weight,
                'shipping_cost'    => $weight * 10000,
                'current_status'   => 'Diproses',
            ]);
        }
    }
}
