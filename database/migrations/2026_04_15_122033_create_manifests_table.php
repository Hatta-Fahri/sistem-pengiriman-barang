<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('manifests', function (Blueprint $table) {
            $table->id();

            // Kode unik jadwal keberangkatan (Contoh: MAN-20260415-001)
            $table->string('manifest_code')->unique();

            // Relasi ke Supir dan Kendaraan (Boleh kosong saat jadwal baru di-draft)
            $table->foreignId('driver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('vehicle_id')->nullable();

            // Parameter penjadwalan
            $table->string('jalur_pengiriman');

            // Statistik
            $table->decimal('total_weight', 10, 2)->default(0);
            $table->integer('total_shipments')->default(0);

            // Status Keberangkatan
            $table->string('status')->default('Draft');

            $table->text('notes')->nullable();
            $table->timestamp('departed_at')->nullable();
            $table->timestamp('arrived_at')->nullable();

            $table->timestamps();
            $table->softDeletes(); // Wajib ada karena Model menggunakan SoftDeletes!
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manifests');
    }
};
