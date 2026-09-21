<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPenanganan extends Model
{
  use HasFactory;
  protected $table = 'log_penanganan';
  protected $fillable = ['pengaduan_id', 'user_id', 'catatan', 'is_public'];
  protected $guarded = ['id'];

  public function pengaduan()
  {
    return $this->belongsTo(Pengaduan::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
