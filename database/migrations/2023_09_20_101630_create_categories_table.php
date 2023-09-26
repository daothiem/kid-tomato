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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->integer('ordering')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('alias');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->text('avatar')->nullable();
            $table->text('meta_video')->nullable();
            $table->text('album')->nullable();
            $table->text('description_detail')->nullable();
            $table->string('price')->nullable()->default('');
            $table->bigInteger('created_by');
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
        Schema::dropIfExists('categories');
    }
};
