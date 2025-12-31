<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contact_info', function (Blueprint $table) {
            $table->id();
            // Contact Information
            $table->string('booking_contact_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('reception_contact_number')->nullable();
            $table->text('address')->nullable();
            $table->string('email')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('youtube_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->text('open_hours')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_info');
    }
};
