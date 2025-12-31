<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('photo_videos', function (Blueprint $table) {
            $table->id();
            $table->string('video_path')->nullable();
            $table->string('video_url')->nullable();
            $table->string('caption');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('photo_videos');
    }
};