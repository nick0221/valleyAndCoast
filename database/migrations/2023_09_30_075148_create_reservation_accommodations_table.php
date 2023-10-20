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
        Schema::create('reservation_accommodations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accommodation_id')->references('id')->on('accommodations')->cascadeOnDelete();
            $table->foreignId('reservation_id')->references('id')->on('reservations')->cascadeOnDelete();
            $table->float('accommodationPrice', 8, 2);
            $table->float('totalAmtDue', 8, 2)->nullable();
            $table->boolean('withBreakfast')->default(0);
            $table->string('bedtype')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_accommodations');
    }
};
