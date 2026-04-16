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

            // Relasi ke Supir (Kurir) dan Kendaraan
            // Dibuat nullable agar bisa disimpan sebagai "Draft" sebelum supir/truk ditentukan
            $table->foreignId('courier_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();

            // Parameter penjadwalan
            $table->string('jalur_pengiriman');

            // Statistik
            $table->decimal('total_weight', 10, 2)->default(0);
            $table->integer('total_shipments')->default(0);

            // Status Keberangkatan (Draft, Menunggu Muatan, Sedang Jalan, Selesai)
            $table->string('status')->default('Draft');

            $table->text('notes')->nullable();
            $table->timestamp('departed_at')->nullable();
            $table->timestamp('arrived_at')->nullable();

            $table->timestamps();
            $table->softDeletes(); // Wajib ada karena Model menggunakan SoftDeletes!
        });

        // Menambahkan kolom manifest_id ke tabel shipments secara otomatis
        // agar kita tahu resi mana yang masuk ke manifest ini
        if (!Schema::hasColumn('shipments', 'manifest_id')) {
            Schema::table('shipments', function (Blueprint $table) {
                $table->foreignId('manifest_id')->nullable()->after('id')->constrained('manifests')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('shipments', 'manifest_id')) {
            Schema::table('shipments', function (Blueprint $table) {
                $table->dropForeign(['manifest_id']);
                $table->dropColumn('manifest_id');
            });
        }

        Schema::dropIfExists('manifests');
    }
};
