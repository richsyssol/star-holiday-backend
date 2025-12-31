<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hotel_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->decimal('number', 10, 2);
            $table->string('suffix')->nullable();
            $table->integer('decimals')->default(0);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotel_statistics');
    }
};