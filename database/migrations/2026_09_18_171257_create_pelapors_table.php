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
    Schema::create('pelapor', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Jika hybrid login
      $table->string('nama_lengkap', 150)->nullable();
      $table->string('npm_nip', 50)->nullable();
      $table->string('status_pelapor', 100)->nullable();
      $table->string('kontak_wa', 20)->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('pelapor');
  }
};
