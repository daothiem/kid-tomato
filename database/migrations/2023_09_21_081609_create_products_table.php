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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('avatar', 255)->default('');
            $table->text('images')->nullable();
            $table->string('alias', 255)->default('');
            $table->string('name', 255)->default('');
            $table->boolean('is_deleted')->default(false);
            $table->boolean('available')->default(false);
            $table->integer('order_by')->default(-1);
            $table->boolean('is_hot')->default(false);
            $table->boolean('is_new')->default(false);
            $table->double('price')->default(0);
            $table->double('price_delivery')->default(0);
            $table->double('price_discount')->default(0);
            $table->double('happy_price')->default(0);
            $table->double('tax')->default(0);
            $table->integer('created_by')->default(0);
            $table->integer('order_by_special')->default(-1);
            $table->integer('ordering')->default(-1);
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_key_word')->nullable();
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
