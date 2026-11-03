<?php

namespace App\Http\Controllers;

use App\Models\Novedad;

class NoveltyController extends Controller
{
  public function index()
  {
    $novedades = Novedad::orderByDesc('fecha')->orderByDesc('id')->paginate(12);

    return view('pages.novedades', ['novedades' => $novedades]);
  }
}
