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

            $table->string('sender_name');
            $table->string('sender_phone');
            $table->text('sender_address');

            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->text('receiver_address');

            $table->text('item_description');
            $table->decimal('weight', 8, 2);
            $table->decimal('distance', 8, 2);
            $table->decimal('shipping_cost', 12, 2);

            $table->foreignId('courier_id')->nullable()->constrained('users')->nullOnDelete();
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
