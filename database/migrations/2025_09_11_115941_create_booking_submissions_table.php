<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('booking_date');
            $table->string('phone_number');
            $table->enum('room_type', [
                '2_bedded_super_deluxe_ac_couple_rooms',
                '4_bedded_super_deluxe_ac_family_rooms',
                '6_bedded_super_deluxe_ac_family_suite'
            ]);
            $table->enum('day', [
                'sunday',
                'monday', 
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday'
            ]);
            $table->text('message')->nullable();
            $table->boolean('is_viewed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_submissions');
    }
};