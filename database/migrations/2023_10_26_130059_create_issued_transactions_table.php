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
        Schema::create('issued_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('tranReference')->nullable();
            $table->string('notes')->nullable();
            $table->integer('tranStatus')->default(1)->nullable();
            $table->foreignIdFor(\App\Models\Staff::class, 'issuedBy');
            $table->foreignIdFor(\App\Models\User::class, 'user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issued_transactions');
    }
};
