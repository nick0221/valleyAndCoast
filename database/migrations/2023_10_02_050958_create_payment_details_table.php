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
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->references('id')->on('reservations')->cascadeOnDelete();
            $table->string('payMethod');
            $table->string('cardNumber')->nullable();
            $table->string('cardType')->nullable();
            $table->string('cardHolder')->nullable();
            $table->longText('notes')->nullable();
            $table->double('totalCharge', 8,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_details');
    }
};
