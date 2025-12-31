<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_saputara', function (Blueprint $table) {
            $table->id();
            $table->longText('about_content');
            $table->string('about_image');
            $table->longText('sightseeing_content');
            $table->string('sightseeing_image');
            $table->json('video_testimonials')->nullable();
            $table->json('gallery')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_saputara');
    }
};