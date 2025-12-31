<?php
// database/migrations/xxxx_xx_xx_create_resort_videos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('resort_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Star Holiday Home Resort');
            $table->text('welcome_text')->default('Welcome to');
            $table->text('description');
            $table->string('youtube_url')->nullable();
            $table->string('uploaded_video_path')->nullable();
            $table->boolean('use_uploaded_video')->default(false);
            $table->boolean('autoplay')->default(true);
            $table->boolean('mute')->default(true);
            $table->boolean('loop')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('resort_videos');
    }
};