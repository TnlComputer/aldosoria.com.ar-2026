<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Novelty extends Model
{
  use HasFactory;

  protected $table = 'novedades';

  protected $guarded = [];
}
