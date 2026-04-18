<?php

namespace Database\Seeders;

use App\Models\ShippingRate;
use Illuminate\Database\Seeder;

class ShippingRateSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. DATA RUTE DALAM KOTA (20 KECAMATAN MEDAN)
        // ==========================================
        $medanKecamatan = [
            'Medan Amplas', 'Medan Area', 'Medan Barat', 'Medan Baru', 'Medan Belawan',
            'Medan Deli', 'Medan Denai', 'Medan Helvetia', 'Medan Johor', 'Medan Kota',
            'Medan Labuhan', 'Medan Maimun', 'Medan Marelan', 'Medan Perjuangan',
            'Medan Petisah', 'Medan Polonia', 'Medan Sunggal', 'Medan Tembung',
            'Medan Timur', 'Medan Tuntungan'
        ];

        foreach ($medanKecamatan as $kecamatan) {
            ShippingRate::create([
                'origin_city'           => 'Medan', // Sesuai nama gudang pusat
                'destination_city'      => $kecamatan,
                'jalur_pengiriman'      => 'Dalam Kota',
                'estimated_distance_km' => rand(5, 25), // Jarak acak 5-25 KM
                'cost_per_kg'           => 3000, // Tarif flat dalam kota
            ]);
        }

        // ==========================================
        // 2. DATA RUTE LUAR KOTA (LINTAS SUMATERA)
        // ==========================================
        $luarKotaRates = [
            // LINTAS TIMUR
            ['destination' => 'Tebing Tinggi', 'jalur' => 'Lintas Timur', 'jarak' => 80, 'harga' => 4000],
            ['destination' => 'Pematangsiantar', 'jalur' => 'Lintas Timur', 'jarak' => 128, 'harga' => 5000],
            ['destination' => 'Kisaran', 'jalur' => 'Lintas Timur', 'jarak' => 160, 'harga' => 6000],
            ['destination' => 'Rantau Prapat', 'jalur' => 'Lintas Timur', 'jarak' => 280, 'harga' => 8000],

            // LINTAS UTARA
            ['destination' => 'Binjai', 'jalur' => 'Lintas Utara', 'jarak' => 22, 'harga' => 3500],
            ['destination' => 'Stabat', 'jalur' => 'Lintas Utara', 'jarak' => 50, 'harga' => 4000],

            // LINTAS SELATAN
            ['destination' => 'Balige', 'jalur' => 'Lintas Selatan', 'jarak' => 240, 'harga' => 8500],
            ['destination' => 'Tarutung', 'jalur' => 'Lintas Selatan', 'jarak' => 300, 'harga' => 9500],
            ['destination' => 'Padang Sidempuan', 'jalur' => 'Lintas Selatan', 'jarak' => 380, 'harga' => 12000],

            // LINTAS BARAT
            ['destination' => 'Berastagi', 'jalur' => 'Lintas Barat', 'jarak' => 66, 'harga' => 4000],
            ['destination' => 'Kabanjahe', 'jalur' => 'Lintas Barat', 'jarak' => 76, 'harga' => 4500],
            ['destination' => 'Sidikalang', 'jalur' => 'Lintas Barat', 'jarak' => 150, 'harga' => 7000],
        ];

        foreach ($luarKotaRates as $rate) {
            ShippingRate::create([
                'origin_city'           => 'Medan',
                'destination_city'      => $rate['destination'],
                'jalur_pengiriman'      => $rate['jalur'],
                'estimated_distance_km' => $rate['jarak'],
                'cost_per_kg'           => $rate['harga'],
            ]);
        }
    }
}
