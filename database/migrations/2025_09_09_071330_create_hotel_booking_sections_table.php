<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_xx_xx_xxxxxx_create_hotel_booking_sections_table.php
public function up()
{
    Schema::create('hotel_booking_sections', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable();
        $table->string('button_text')->default('BOOK NOW');
        $table->string('button_link')->default('/bookform');
        $table->enum('video_type', ['upload', 'url'])->default('url');
        $table->string('video_url')->nullable();
        $table->string('uploaded_video')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_booking_sections');
    }
};
