<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();

            $table->string('origin_city');
            $table->string('destination_city');

            // Jarak estimasi tidak wajib diisi karena tarif dihitung per rute kota, bukan per KM
            $table->decimal('estimated_distance_km', 8, 2)->nullable();

            // Tarif dasar per kilogram
            $table->decimal('cost_per_kg', 12, 2);

            $table->timestamps();

            // Mencegah admin memasukkan rute kembar (misal: Medan ke Siantar ada 2 harga)
            $table->unique(['origin_city', 'destination_city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
    }
};
