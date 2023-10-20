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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Customer::class, 'customer_id');
            $table->date('checkIn');
            $table->time('checkInTime')->nullable();
            $table->date('checkOut');
            $table->time('checkOutTime')->nullable();
            $table->foreignIdFor(\App\Models\User::class, 'user_id')->nullable();
            $table->foreignIdFor(\App\Models\User::class, 'last_edited_by_id')->nullable();
            $table->foreignIdFor(\App\Models\CustomerCompany::class, 'customer_company_id')->nullable();
            $table->string('status')->nullable();
            $table->string('paymentStatus')->nullable();
            $table->string('tranReference')->nullable();
            $table->json('guest')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
