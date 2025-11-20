<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul slider (bisa untuk alt text)
            $table->string('slug')->unique(); // Slug unik untuk slider
            $table->text('description')->nullable(); // Deskripsi singkat (opsional)
            $table->string('image'); // Path gambar slider
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
