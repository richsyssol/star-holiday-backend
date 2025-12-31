<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('family_room_abouts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('tagline');
            $table->json('descriptions');
            $table->json('specs');
            $table->json('amenities');
            $table->json('images')->nullable();
            $table->json('booking_button');
            $table->json('styling');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('family_room_abouts');
    }
};