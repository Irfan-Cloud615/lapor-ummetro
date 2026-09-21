<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
  use HasFactory;

  protected $table = 'faqs';

  protected $fillable = [
    'pertanyaan',
    'jawaban',
    'urutan',
    'is_active',
  ];

  protected $casts = [
    'is_active' => 'boolean',
    'urutan' => 'integer',
  ];
}
