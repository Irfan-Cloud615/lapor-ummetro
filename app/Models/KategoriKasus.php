<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriKasus extends Model
{
    use HasFactory;
    protected $table = 'kategori_kasus';
    protected $fillable = ['nama_kategori', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'kategori_id');
    }
}
