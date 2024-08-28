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
        Schema::create('shop_data', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('number')->nullable();
            $table->string('address')->nullable();
            $table->string('fio')->nullable();
            $table->string('inn')->nullable();
            $table->string('ogrnip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_data');
    }
};
