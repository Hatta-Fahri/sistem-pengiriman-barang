<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique()->index();

            // Data Pengirim
            $table->string('sender_name');
            $table->string('sender_phone');
            $table->text('sender_address');

            // Data Penerima
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->text('receiver_address');

            // Data Rute & Jalur
            $table->string('origin_city');
            $table->string('destination_city');
            $table->string('jalur_pengiriman');

            // Detail Barang & Biaya
            $table->text('item_description');
            $table->decimal('weight', 8, 2);
            $table->decimal('distance', 8, 2)->nullable(); 
            $table->decimal('shipping_cost', 12, 2);

            // Relasi Penjadwalan (Pengganti courier_id)
            // Null berarti "Belum Dijadwalkan / Masih di Gudang"
            $table->unsignedBigInteger('manifest_id')->nullable();

            // Menggunakan Enum/String untuk status
            $table->string('current_status')->default('Diproses');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
