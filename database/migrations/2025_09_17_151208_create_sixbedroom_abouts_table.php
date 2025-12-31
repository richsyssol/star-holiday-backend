<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_sixbedroom_abouts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sixbedroom_abouts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('tagline');
            $table->json('descriptions');
            $table->json('specs');
            $table->json('amenities');
            $table->json('images')->nullable();
            $table->json('booking_button');
            $table->json('styling');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sixbedroom_abouts');
    }
};