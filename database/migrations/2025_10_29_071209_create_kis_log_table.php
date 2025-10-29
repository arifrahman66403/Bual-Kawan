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
        Schema::create('kis_log', function (Blueprint $table) {
            $table->id(); // INT AUTO_INCREMENT
            $table->unsignedBigInteger('user_id')->nullable(); // FK -> kis_user.id
            $table->char('pengunjung_id', 36)->nullable(); // FK -> kis_pengunjung.uid
            $table->text('aksi')->nullable(); // action summary
            $table->timestamps();

            // foreign keys
            $table->foreign('user_id')->references('id')->on('kis_user')->nullOnDelete();
            $table->foreign('pengunjung_id')->references('uid')->on('kis_pengunjung')->nullOnDelete();

            $table->index('user_id');
            $table->index('pengunjung_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kis_log');
    }
};
