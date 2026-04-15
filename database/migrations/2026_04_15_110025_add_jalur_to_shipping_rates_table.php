<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_rates', function (Blueprint $table) {
            // Menambahkan kolom jalur setelah kota tujuan
            $table->string('jalur_pengiriman')->after('destination_city')->default('Lainnya');
        });
    }

    public function down(): void
    {
        Schema::table('shipping_rates', function (Blueprint $table) {
            $table->dropColumn('jalur_pengiriman');
        });
    }
};
