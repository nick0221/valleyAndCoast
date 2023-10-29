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
        Schema::create('customer_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Accommodation::class, 'accommodation_id');
            $table->string('customerName');
            $table->date('check_in');
            $table->date('check_out');
            $table->string('contact');
            $table->string('email')->nullable();
            $table->string('status')->nullable()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_reservations');
    }
};
