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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('salesman_id')->nullable();
            $table->foreign('salesman_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('product_id');
            $table->string('productName');
            $table->string('productPrice');
            $table->string('productAddon')->nullable();
            $table->string('addonPrice')->nullable();
            $table->string('productVariation')->nullable();
            $table->string('VariationPrice')->nullable();
            $table->string('drinkFlavour')->nullable();
            $table->string('drinkFlavourPrice')->nullable();
            $table->string('productQuantity');
            $table->string('totalPrice');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
