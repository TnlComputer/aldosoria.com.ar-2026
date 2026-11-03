<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Marcas_images_autos extends Model
{
  use HasFactory;

  public $guarded = [];

  public function marcas_autos(): BelongsToMany
  {
    return $this->belongsToMany(Marcas_autos::class);
  }
}
