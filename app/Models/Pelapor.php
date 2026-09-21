<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelapor extends Model
{
    use HasFactory;

    protected $table = 'pelapor';
    protected $fillable = ['user_id', 'nama_lengkap', 'npm_nip', 'status_pelapor', 'kontak_wa'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function pengaduan() {
        return $this->hasOne(Pengaduan::class);
    }
}
