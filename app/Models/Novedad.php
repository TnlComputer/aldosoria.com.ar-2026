<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Novedad extends Model
{
  use HasFactory;

  protected $table = 'novedades';

  protected $guarded = [];

  // protected $fillable = [
  //   "novedades",
  //   "fecha",
  //   "uri_novedad",
  //   "tipo_novedad",
  //   "foto_novedad"
  // ];
}
