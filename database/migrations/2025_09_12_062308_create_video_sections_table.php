<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_video_sections_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('video_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->enum('video_type', ['youtube', 'upload'])->default('youtube');
            $table->text('youtube_url')->nullable();
            $table->string('uploaded_video_path')->nullable();
            $table->boolean('autoplay')->default(true);
            $table->boolean('muted')->default(true);
            $table->boolean('loop')->default(true);
            $table->boolean('show_controls')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('video_sections');
    }
};