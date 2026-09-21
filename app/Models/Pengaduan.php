<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
  use HasFactory;

  protected $table = 'pengaduan';
  protected $fillable = [
    'kode_pengaduan',
    'pelapor_id',
    'korban_id',
    'terlapor_id',
    'kategori_id',
    'tanggal_kejadian',
    'waktu_kejadian',
    'lokasi_kejadian',
    'kronologi',
    'saksi',
    'pin_akses',
    'status_pengaduan',
    'satgas_id',
  ];

  protected $casts = [
    'tanggal_kejadian' => 'date',
  ];

  // Relasi ke Entitas yang Terlibat
  public function pelapor()
  {
    return $this->belongsTo(Pelapor::class);
  }

  public function korban()
  {
    return $this->belongsTo(Korban::class);
  }

  public function terlapor()
  {
    return $this->belongsTo(Terlapor::class);
  }

  public function kategori()
  {
    return $this->belongsTo(KategoriKasus::class, 'kategori_id');
  }

  public function lampiran_bukti()
  {
    return $this->hasMany(LampiranBukti::class);
  }


  public function satgas()
  {
    return $this->belongsTo(User::class, 'satgas_id');
  }

  public function logPenanganan()
  {
    return $this->hasMany(LogPenanganan::class, 'pengaduan_id')->with('user')->oldest();
  }
}
