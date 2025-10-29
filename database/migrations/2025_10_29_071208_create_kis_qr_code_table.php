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
        Schema::create('kis_qr_code', function (Blueprint $table) {
            $table->id(); // INT AUTO_INCREMENT (sesuai desain pada gambar kolom id)
            $table->char('pengunjung_id', 36); // FK -> kis_pengunjung.uid
            $table->text('qr_code')->nullable(); // store QR payload/base64/path
            $table->dateTime('berlaku_mulai')->nullable();
            $table->dateTime('berlaku_sampai')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('edited_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // foreign keys
            $table->foreign('pengunjung_id')->references('uid')->on('kis_pengunjung')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('kis_user')->nullOnDelete();
            $table->foreign('edited_by')->references('id')->on('kis_user')->nullOnDelete();
            $table->foreign('deleted_by')->references('id')->on('kis_user')->nullOnDelete();

            $table->index('pengunjung_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kis_qr_code');
    }
};
