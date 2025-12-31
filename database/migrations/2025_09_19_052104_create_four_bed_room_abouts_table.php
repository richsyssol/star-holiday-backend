<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('four_bed_room_abouts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('6 Bedded super deluxe AC family Suite');
            $table->text('tagline')->nullable();
            $table->json('descriptions')->nullable();
            $table->json('specs')->nullable(); // For room specifications
            $table->json('amenities')->nullable();
            $table->json('images')->nullable(); // Will store array of image paths
            $table->json('styling')->nullable(); // For background and maxWidth
            $table->json('booking_button')->nullable(); // For button text and URL
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('four_bed_room_abouts');
    }
};