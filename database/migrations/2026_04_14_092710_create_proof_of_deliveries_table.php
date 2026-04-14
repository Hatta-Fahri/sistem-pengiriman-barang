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
    Schema::create('proof_of_deliveries', function (Blueprint $table) {
        $table->id();
        $table->foreignId('shipment_id')->unique()->constrained('shipments')->cascadeOnDelete();
        $table->string('photo_path');
        $table->string('received_by_name');
        $table->timestamp('delivered_at');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proof_of_deliveries');
    }
};
