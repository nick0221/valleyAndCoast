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
        Schema::create('received_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->references('id')->on('inventories')->cascadeOnDelete();
            $table->unsignedBigInteger('mass_receive_id')->nullable()->default(0);
            $table->foreign('mass_receive_id')->references('id')->on('received_stocks')->cascadeOnDelete();
            $table->longText('remarks')->nullable();
            $table->string('tranType')->nullable();
            $table->integer('qty')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('received_stocks');
    }
};
