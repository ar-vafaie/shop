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
            $table->timestamps();
            $table->softDeletes()->nullable();
            $table->string('name')->index();
            $table->text('description');
            $table->decimal('stock');
            $table->decimal('price',);
            $table->unsignedBigInteger('buy_count');

        });
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('product_id')->constrained();
        });
        Schema::create('product_comments', function(Blueprint $table){
            $table->id();
            $table->timestamps();
            $table->string('comment');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('user_id')->constrained('users');
            $table->stirng('author_name');
            $table->boolean('is_showable')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_comments');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
    }
};
