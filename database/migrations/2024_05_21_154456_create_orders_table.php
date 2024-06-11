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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('salesman_id');
            $table->foreign('salesman_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('total_bill');
            $table->decimal('received_cash', 8, 2);
            $table->decimal('return_change', 8, 2);
            $table->string('ordertype')->nullable();
            $table->integer('status')->default('2');
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
