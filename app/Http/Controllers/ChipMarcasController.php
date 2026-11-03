<?php

namespace App\Http\Controllers;

use App\Models\ChipMarcas;
use App\Models\Marcas_autos;
use App\Models\Marcas_images_autos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChipMarcasController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $marcasLogos = Marcas_images_autos::all();
    return view('pages.marcas', ['marcasLogos' => $marcasLogos]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(Request $request)
  {
    $dd = $request;
    $marcasLogos = Marcas_images_autos::find($request->id);
    $marcasAutos = DB::table('marcas_autos')
      ->where('mautos.marca = $marcasLogos->marca')
      ->select('id', 'marca', 'modelo', 'anio', 'modelo_motor', 'transmicion', 'combustible', 'potencia_original');
    $marcasLogos = Marcas_images_autos::all();

    return view('pages.marcas', ['marcasAutos' => $marcasAutos, 'marcasLogos' => $marcasLogos]);
    // return dd($dd);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(ChipMarcas $chipMarcas)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, ChipMarcas $chipMarcas)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(ChipMarcas $chipMarcas)
  {
    //
  }
}
