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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('itemname');
            $table->longText('itemdesc')->nullable();
            $table->foreignIdFor(\App\Models\ItemCategory::class, 'category_id')->nullable();
            $table->bigInteger('remainingStocks')->nullable();
            $table->string('image')->nullable();
            $table->foreignIdFor(\App\Models\User::class, 'created_by')->nullable();
            $table->foreignIdFor(\App\Models\User::class, 'last_edited_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
