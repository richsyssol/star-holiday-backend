<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  // database/migrations/xxxx_xx_xx_create_testimonials_table.php
public function up()
{
    Schema::create('testimonials', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('project');
        $table->text('quote');
        $table->integer('rating')->default(5);
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
