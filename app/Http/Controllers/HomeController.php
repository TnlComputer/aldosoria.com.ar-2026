<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Marcas_autos;
use App\Models\Novedad;

class HomeController extends Controller
{
  public function index()
  {
    return view('pages.home', [
      'cursos' => Curso::leftJoin('monedas as mon', 'mon.id', '=', 'cursos.moneda_id')
        ->select('cursos.*', 'mon.simbolo')
        ->orderByDesc('cursos.id')
        ->limit(3)
        ->get(),
      'novedades' => Novedad::where('tipo_novedad', 4)->orderByDesc('fecha')->limit(3)->get(),
      'fichas' => Marcas_autos::count(),
      'marcas' => Marcas_autos::distinct()->count('marca'),
    ]);
  }
}
