<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelos_autos extends Model
{
  use HasFactory;

  public $guarded = [];

  // public function marcas_images_logos(): BelongsToMany
  // {
  //   return $this->belongsToMany(Marcas_images_autos::class);
  // }
}
