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
        Schema::create('order_mapping', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('order_yookassa_id');
            $table->integer('shop_id');
            $table->integer('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_mapping');
    }
};
