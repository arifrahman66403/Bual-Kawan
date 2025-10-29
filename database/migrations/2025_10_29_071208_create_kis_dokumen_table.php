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
        Schema::create('kis_dokumen', function (Blueprint $table) {
            $table->char('uid', 36)->primary(); // CHAR(36)
            $table->char('pengunjung_id', 36); // FK -> kis_pengunjung.uid
            $table->string('file_spt', 255)->nullable(); // path/file name
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
        Schema::dropIfExists('kis_dokumen');
    }
};
