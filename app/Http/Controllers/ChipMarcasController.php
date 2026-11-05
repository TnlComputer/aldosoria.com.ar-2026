<?php

namespace App\Http\Controllers;

use App\Models\ChipMarcas;
use App\Models\Marcas_autos;
use App\Models\Marcas_images_autos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChipMarcasController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $marcasLogos = Marcas_images_autos::all();

    return view('pages.marcas', ['marcasLogos' => $marcasLogos] + $this->seo($request));
  }

  /**
   * Titulo, descripcion y URL canonica propios para cada ficha y cada marca,
   * asi Google indexa "chiptuning <marca> <modelo>" como paginas separadas.
   */
  private function seo(Request $request): array
  {
    $ficha = (int) $request->query('ficha', 0);
    $vehiculo = $ficha ? Marcas_autos::find($ficha) : null;

    if ($vehiculo) {
      $nombre = trim(preg_replace('/\s+/', ' ', "{$vehiculo->marca} {$vehiculo->modelo} {$vehiculo->motor}"));
      $cifras = array_filter([
        $vehiculo->potencia_original ? 'de ' . $vehiculo->potencia_original : null,
        $vehiculo->stage1 ? 'a ' . $vehiculo->stage1 . ' en Stage 1' : null,
      ]);

      return [
        'seoPagina' => "Chiptuning {$nombre}",
        'seoDescripcion' => Str::limit("Chiptuning {$nombre}: potencia y torque antes y después" . ($cifras ? ' (' . implode(' ', $cifras) . ')' : '') . '. Medido en banco de rodillos en Morón.', 160),
        'seoCanonical' => route('chipMarcas', ['ficha' => $vehiculo->id]),
      ];
    }

    $marca = trim((string) $request->query('marca', ''));
    if ($marca !== '' && Marcas_autos::where('marca', $marca)->exists()) {
      return [
        'seoPagina' => "Chiptuning {$marca}: potencia y torque por modelo",
        'seoDescripcion' => "Fichas de chiptuning para {$marca}: cuánta potencia y torque gana cada modelo con Stage 1, 2 y 3. Medido en banco de rodillos en Soria Perfomance, Morón.",
        'seoCanonical' => route('chipMarcas', ['marca' => $marca]),
      ];
    }

    // Busquedas libres (?q=) y cualquier otra combinacion apuntan al buscador general
    return ['seoCanonical' => route('chipMarcas')];
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
