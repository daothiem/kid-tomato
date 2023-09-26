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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('keywords')->nullable(true);
            $table->string('avatar')->nullable(true);
            $table->text('content')->nullable(true);
            $table->integer('category_id');
            $table->integer('ordering');
            $table->string('alias');
            $table->bigInteger('created_by');
            $table->text('meta_title')->nullable(true);
            $table->text('meta_description')->nullable(true);
            $table->text('meta_key_word')->nullable(true);
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
        Schema::dropIfExists('news');
    }
};
