<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yookassa_payments', function (Blueprint $table) {
            $table->id();
            $table->string('yookassa_id', 50)->nullable();
            $table->text('body')->nullable();
            $table->text('cart_body');
            $table->integer('cart_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yookassa_payments');
    }
};
