<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            // Plat nomor kendaraan, dibuat unik agar tidak ada duplikasi data
            $table->string('license_plate')->unique();

            // Jenis kendaraan (misal: 'Motor', 'Mobil Box', 'Truk Pick Up')
            $table->string('type');

            // Membuat kolom 'created_at' dan 'updated_at' secara otomatis
            $table->timestamps();

            // Membuat kolom 'deleted_at' untuk fitur Soft Deletes (Audit Trail)
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        // Fungsi ini akan dijalankan jika kamu melakukan perintah: php artisan migrate:rollback
        Schema::dropIfExists('vehicles');
    }
};
