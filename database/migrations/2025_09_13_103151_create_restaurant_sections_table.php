<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('restaurant_sections', function (Blueprint $table) {
            $table->id();
            $table->string('heading')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('restaurant_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_section_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('restaurant_images');
        Schema::dropIfExists('restaurant_sections');
    }
};