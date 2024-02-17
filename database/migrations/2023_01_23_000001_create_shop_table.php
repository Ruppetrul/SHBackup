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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            $table->string('name', 100)->unique();
            $table->string('db_name', 30)->unique();
            $table->enum('payment_status', ['active', 'inactive', 'trial'])->default('active');
            $table->enum('state', ['not_created', 'created', 'deleted'])->default('not_created');
            $table->timestamp('last_used_at')->nullable();
            $table->tinyInteger('is_attachment_tg')->default(0);
            $table->string('tg_name', 100)->default('')->nullable();
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
