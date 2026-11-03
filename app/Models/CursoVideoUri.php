<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursoVideoUri extends Model
{
  use HasFactory;

  protected $table = 'cursos_videos_uris';

    // protected $fillable = [
    //     'id_curso',
    //     'nro_video_id',
    //     'descripcion',
    //     'uri_video',
    //     'tipo_curso',
    //     'archivo1_curso',
    //     'archivo2_curso',
    //     'img_video',
    //     'nombre_curso',
    //     'nombre_video',
    //     'video_curso_id',
    // ];
  protected $guarded = [];
  
}
