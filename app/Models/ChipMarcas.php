<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChipMarcas extends Model
{
  use HasFactory;

  protected $table = 'marcas_autos';

  protected $guarded = [];
}
