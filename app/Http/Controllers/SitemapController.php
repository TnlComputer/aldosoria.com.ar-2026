<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Marcas_autos;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
  /**
   * Mapa del sitio para buscadores: paginas publicas, cursos, marcas y fichas de chiptuning.
   */
  public function index(): Response
  {
    $urls = [];
    $agregar = function (string $url, $fecha = null, string $prioridad = '0.5') use (&$urls) {
      $urls[] = ['loc' => $url, 'lastmod' => $fecha ? date('Y-m-d', strtotime((string) $fecha)) : null, 'priority' => $prioridad];
    };

    $agregar(route('home'), null, '1.0');
    foreach (['chiptuning', 'chipMarcas', 'cursos'] as $ruta) {
      $agregar(route($ruta), null, '0.9');
    }
    foreach (['mecanica', 'electronica', 'representacion', 'mantenimiento', 'novedad', 'contacto'] as $ruta) {
      $agregar(route($ruta), null, '0.7');
    }

    foreach (Curso::orderBy('id')->get(['id', 'updated_at']) as $curso) {
      $agregar(route('cursoDetalle.show', $curso->id), $curso->updated_at, '0.8');
    }

    foreach (Marcas_autos::select('marca')->distinct()->orderBy('marca')->pluck('marca') as $marca) {
      $agregar(route('chipMarcas', ['marca' => $marca]), null, '0.6');
    }

    foreach (Marcas_autos::orderBy('id')->get(['id', 'updated_at']) as $ficha) {
      $agregar(route('chipMarcas', ['ficha' => $ficha->id]), $ficha->updated_at, '0.6');
    }

    return response()
      ->view('sitemap', ['urls' => $urls])
      ->header('Content-Type', 'application/xml; charset=UTF-8');
  }
}
