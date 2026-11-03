<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursoPago extends Model
{
  use HasFactory;

  protected $table = 'cursos_pagos_videos';

  // protected $casts = ['vto_curso' => 'datetime:d-m-Y',];

  protected $fillable = [
    "user_curso_id",
    "video_curso_id",
    "vto_curso"
  ];
}
