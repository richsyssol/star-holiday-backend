<?php
// database/migrations/xxxx_xx_xx_create_about_us_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('about_us', function (Blueprint $table) {
            $table->id();
            $table->string('welcome_title');
            $table->string('main_title');
            $table->text('description_1');
            $table->text('description_2');
            $table->string('button_text')->default('LEARN MORE');
            $table->string('image_1')->nullable();
            $table->string('image_2')->nullable();
            $table->string('image_3')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('about_us');
    }
};