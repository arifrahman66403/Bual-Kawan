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
        Schema::create('kis_peserta_kunjungan', function (Blueprint $table) {
            $table->char('uid', 36)->primary(); // CHAR(36)
            $table->char('pengunjung_id', 36); // FK to kis_pengunjung.uid
            $table->string('nama', 150);
            $table->string('nip', 50)->nullable();
            $table->string('jabatan', 150)->nullable();
            $table->boolean('is_perwakilan')->default(false);
            $table->string('email', 100)->nullable();
            $table->string('wa', 20)->nullable();
            $table->string('file_ttd', 255)->nullable();

            // audit as INT -> kis_user.id
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
            $table->index('nip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kis_peserta_kunjungan');
    }
};
