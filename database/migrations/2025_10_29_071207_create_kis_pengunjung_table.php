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
        Schema::create('kis_pengunjung', function (Blueprint $table) {
            $table->char('uid', 36)->primary(); // CHAR(36) UUID
            $table->string('tipe_pengunjung', 50); // e.g., 'instansi pemerintah', 'perorangan', etc.
            $table->string('kode_daerah', 50)->nullable();
            $table->string('nama_instansi', 150);
            $table->string('satuan_kerja', 150)->nullable();
            $table->text('tujuan')->nullable();
            $table->date('tgl_kunjungan')->nullable();
            $table->string('file_kunjungan', 255)->nullable(); // path/file name
            $table->string('nama_perwakilan', 100)->nullable();
            $table->string('email_perwakilan', 100)->nullable();
            $table->string('wa_perwakilan', 20)->nullable();
            $table->string('jabatan_perwakilan', 100)->nullable();
            $table->enum('status', ['pengajuan','disetujui','kunjungan','selesai'])->default('pengajuan');

            // audit references -> INT referencing kis_user.id
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('edited_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // foreign keys
            $table->foreign('created_by')->references('id')->on('kis_user')->nullOnDelete();
            $table->foreign('edited_by')->references('id')->on('kis_user')->nullOnDelete();
            $table->foreign('deleted_by')->references('id')->on('kis_user')->nullOnDelete();

            // indexes for common queries
            $table->index('kode_daerah');
            $table->index('tgl_kunjungan');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kis_pengunjung');
    }
};
