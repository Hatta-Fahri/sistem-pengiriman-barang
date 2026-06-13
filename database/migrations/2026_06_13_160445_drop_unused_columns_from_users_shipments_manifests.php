<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus users.vehicle_id — perlu drop foreign key dulu sebelum drop column
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id']);
            $table->dropColumn('vehicle_id');
        });

        // Hapus shipments.distance — nullable, tidak pernah diisi
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn('distance');
        });

        // Hapus manifests.arrived_at — tidak pernah diisi
        Schema::table('manifests', function (Blueprint $table) {
            $table->dropColumn('arrived_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete()->after('role');
        });

        Schema::table('shipments', function (Blueprint $table) {
            $table->decimal('distance', 8, 2)->nullable();
        });

        Schema::table('manifests', function (Blueprint $table) {
            $table->timestamp('arrived_at')->nullable();
        });
    }
};
