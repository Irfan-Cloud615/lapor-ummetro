<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LampiranBukti extends Model
{
    use HasFactory;

    protected $table = 'lampiran_bukti';
    protected $fillable = ['pengaduan_id', 'nama_file', 'tipe_file'];

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class);
    }
}
