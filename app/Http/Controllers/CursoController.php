<?php

namespace App\Http\Controllers;

use App\Models\Curso;

class CursoController extends Controller
{
  public function index()
  {
    $cursos = Curso::leftJoin('monedas as mon', 'mon.id', '=', 'cursos.moneda_id')
      ->select('cursos.*', 'mon.simbolo')
      ->orderByDesc('cursos.id')
      ->paginate(9);

    return view('pages.cursos', ['cursos' => $cursos]);
  }
}
