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
        Schema::create('mass_receives', function (Blueprint $table) {
            $table->id();
            $table->string('tranReference')->nullable();
            $table->string('notes')->nullable();
            $table->integer('tranStatus')->default(1)->nullable();
            $table->foreignIdFor(\App\Models\Staff::class, 'receivedBy');
            $table->foreignIdFor(\App\Models\User::class, 'user_id');
            $table->foreignIdFor(\App\Models\SupplierProfile::class, 'supplier_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mass_receives');
    }
};
