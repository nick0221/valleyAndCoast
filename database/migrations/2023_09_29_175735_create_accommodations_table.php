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
        Schema::create('accommodations', function (Blueprint $table) {
            $table->id();
            $table->string('roomNumber');
            $table->integer('bedCount')->nullable();
            $table->foreignIdFor(\App\Models\BedType::class,'bed_type_id');
            $table->string('maxOccupancy')->nullable();
            $table->float('pricePerNight', 8, 2);
            $table->boolean('availability');
            $table->boolean('isSmokingAllowed')->nullable();
            $table->boolean('hasBalcony')->nullable();
            $table->boolean('isAirconditioned');
            $table->string('roomSize')->nullable();
            $table->json('amenities')->nullable();
            $table->longText('description')->nullable();
            $table->longText('image')->nullable();
            $table->foreignIdFor(\App\Models\User::class,'user_id')->nullable();
            $table->foreignIdFor(\App\Models\User::class,'last_edited_by_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodations');
    }
};
