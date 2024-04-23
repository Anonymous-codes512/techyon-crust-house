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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();

            $table->string('dealImage')->nullable();
            $table->string('dealTitle')->nullable();
            $table->string('dealStatus')->nullable();
            $table->string('dealProductName')->nullable();
            $table->string('dealProductQuantity')->nullable();
            $table->string('dealProductPrice')->nullable();
            $table->string('dealActualPrice')->nullable();
            $table->string('dealDiscountedPrice')->nullable();
            $table->string('dealEndDate')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
