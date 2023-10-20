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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('fullname')->nullable();
            $table->string('gender')->nullable();
            $table->string('contact')->nullable();
            $table->longText('address')->nullable();
            $table->date('dateHired')->nullable();
            $table->date('dateResign')->nullable();
            $table->foreignIdFor(\App\Models\Designation::class, 'designation_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
