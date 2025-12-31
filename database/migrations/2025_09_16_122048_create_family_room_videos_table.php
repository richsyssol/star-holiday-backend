<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('family_room_videos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('video_url')->nullable(); // For YouTube embed links
            $table->string('video_file')->nullable(); // For uploaded videos
            $table->text('review')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_room_videos');
    }
};