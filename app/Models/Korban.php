<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Korban extends Model
{
    use HasFactory;

    protected $table = 'korban';
    protected $fillable = ['nama_lengkap', 'program_studi', 'usia'];

    protected $casts = [
        'usia' => 'integer',
    ];

    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class);
    }
}
