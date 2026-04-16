<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // ID Otomatis (KRR001)
            $table->string('courier_code')->unique()->nullable()->after('id');
            // Data Pribadi
            $table->string('nik', 20)->unique()->nullable()->after('password');
            $table->string('phone', 20)->nullable()->after('nik');
            $table->string('sim_number', 30)->nullable()->after('phone');
            $table->string('sim_type', 15)->nullable()->after('sim_number');
            $table->text('address')->nullable()->after('sim_type');
            // Status Personil
            $table->enum('status', ['Aktif', 'Cuti', 'Berhenti'])->default('Aktif')->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['courier_code', 'nik', 'phone', 'sim_number', 'sim_type', 'address', 'status']);
        });
    }
};
