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
        Schema::create('issued_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->references('id')->on('inventories')->cascadeOnDelete();
            $table->foreignId('issued_transaction_id')->references('id')->on('issued_transactions')->cascadeOnDelete();
            $table->bigInteger('issuedQty');
            $table->string('tranType')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issued_items');
    }
};
