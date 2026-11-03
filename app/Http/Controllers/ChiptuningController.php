<?php

namespace App\Http\Controllers;

use App\Models\Marcas_autos;

class ChiptuningController extends Controller
{
  public function index()
  {
    return view('pages.chiptuning', [
      'marcas' => Marcas_autos::marcasConFichas(),
      'fichas' => Marcas_autos::count(),
    ]);
  }
}
