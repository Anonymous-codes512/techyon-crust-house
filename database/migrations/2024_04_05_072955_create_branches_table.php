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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('branchLocation');
            $table->string('branchName');
            $table->string('branchCode')->unique();
            $table->integer('branchStreatNumber');
            $table->integer('numberOfItems');
            $table->integer('numberOfStaff');
            $table->boolean('riderOption')->nullable();
            $table->boolean('onlineDeliveryOption')->nullable();
            $table->boolean('DiningTableOption')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
