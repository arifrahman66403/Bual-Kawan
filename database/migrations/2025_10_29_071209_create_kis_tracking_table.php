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
        Schema::create('kis_tracking', function (Blueprint $table) {
            $table->id(); // INT AUTO_INCREMENT
            $table->char('pengajuan_id', 36); // FK -> kis_pengunjung.uid (nama kolom sesuai gambar)
            $table->text('catatan')->nullable();
            $table->enum('status', ['disetujui','kunjungan','selesai'])->default('disetujui');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('edited_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // foreign keys
            $table->foreign('pengajuan_id')->references('uid')->on('kis_pengunjung')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('kis_user')->nullOnDelete();
            $table->foreign('edited_by')->references('id')->on('kis_user')->nullOnDelete();
            $table->foreign('deleted_by')->references('id')->on('kis_user')->nullOnDelete();

            $table->index('pengajuan_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kis_tracking');
    }
};
