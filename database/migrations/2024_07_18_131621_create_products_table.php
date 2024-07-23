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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained();
            $table->string('image_thumbnail');
            $table->double('price_regular')->nullable();
            $table->bigInteger('view')->default(0);
            $table->double('price_sale')->nullable();
            $table->longText('content')->nullable();
            $table->string('desciption')->nullable();
            $table->string('material')->nullable();
            $table->tinyInteger('is_show_home')->default(1);
            $table->tinyInteger('is_new')->default(1);
            $table->tinyInteger('is_trending')->default(1);
            $table->tinyInteger('is_sale')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
