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
        Schema::create('house_testings', function (Blueprint $table) {
            $table->id();
            $table->float('Square_Footage');
            $table->integer('Num_Bedrooms');
            $table->integer('Num_Bathrooms');
            $table->integer('Year_Built');
            $table->float('Lot_Size');
            $table->integer('Garage_Size');
            $table->integer('Neighborhood_Quality'); // 1-10
            $table->float('House_Price');
            $table->float('predicted_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('house_testings');
    }
};