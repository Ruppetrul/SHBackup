<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Mini\Models\Enums\ProductStatusEnum;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $statuses = [];
        foreach (ProductStatusEnum::cases() as $status) {
            $statuses[] = $status->value;
        }

        Schema::create('products', static function (Blueprint $table) use ($statuses) {
            $table->id();
            $table->integer('first_media_id')->nullable();
            $table->string('title')->unique();
            $table->decimal('price')->default(0);
            $table->string('count')->default(1);
            $table->string('type')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('body')->nullable();
            $table->enum('status', $statuses);
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
        Schema::dropIfExists('products');
    }
};
