<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'title',
    'videos_curso_cantidad',
    'video_demo_Uri',
    'coast',
    'image_curso',
    'moneda_id',
    'tipo_curso_id'
  ];

  public const CARPETA_IMAGENES = 'img/cursos';

  private const IMAGEN_GENERICA = 'foto_modelo_cursos.png';

  /**
   * URL de la imagen de un curso; si el archivo no existe usa la imagen generica.
   */
  public static function urlImagen(?string $archivo): string
  {
    $archivo = basename((string) $archivo);

    if ($archivo === '' || !is_file(public_path(self::CARPETA_IMAGENES . '/' . $archivo))) {
      $archivo = self::IMAGEN_GENERICA;
    }

    return asset(self::CARPETA_IMAGENES . '/' . $archivo);
  }
}
