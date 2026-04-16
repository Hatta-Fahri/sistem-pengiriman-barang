<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // Kapasitas maksimal muatan dalam satuan Kilogram (Kg)
            $table->decimal('capacity', 10, 2)->default(0)->after('type');

            // Status ketersediaan armada
            $table->string('status')->default('Tersedia')->after('capacity');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['capacity', 'status']);
        });
    }
};
