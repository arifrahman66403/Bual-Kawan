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
        Schema::create('kis_user', function (Blueprint $table) {
            $table->id(); // INT AUTO_INCREMENT
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('user', 50)->unique(); // username
            $table->string('wa', 20)->nullable();
            $table->enum('role', ['superadmin', 'admin', 'operator'])->default('operator');
            $table->string('pass'); // you can rename to 'password' in model if needed
            // audit columns for actions performed by user on other rows are stored as INT referencing kis_user.id
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kis_user');
    }
};
