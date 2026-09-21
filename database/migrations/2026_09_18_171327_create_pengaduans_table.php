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
    Schema::create('pengaduan', function (Blueprint $table) {
      $table->id();
      $table->string('kode_pengaduan', 30)->unique();

      // Relasi Entitas
      $table->foreignId('pelapor_id')->nullable()->constrained('pelapor')->cascadeOnDelete();
      $table->foreignId('korban_id')->nullable()->constrained('korban')->nullOnDelete();
      $table->foreignId('terlapor_id')->nullable()->constrained('terlapor')->cascadeOnDelete();
      $table->foreignId('kategori_id')->nullable()->constrained('kategori_kasus')->restrictOnDelete();

      // Rincian Kejadian
      $table->date('tanggal_kejadian');
      $table->time('waktu_kejadian');
      $table->string('lokasi_kejadian', 255);
      $table->text('kronologi');
      $table->text('saksi')->nullable();

      // Tracking untuk Guest (Non-Login)
      $table->string('pin_akses')->nullable(); // Disimpan dalam bentuk hash

      // Status & Disposisi
      $table->enum('status_pengaduan', ['Baru', 'Diproses', 'Investigasi', 'Selesai', 'Ditolak'])->default('Baru');
      $table->foreignId('satgas_id')->nullable()->constrained('users')->nullOnDelete();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('pengaduan');
  }
};
