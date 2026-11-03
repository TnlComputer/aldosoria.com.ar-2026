<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\CursoVideoUri;
use App\Models\Moneda;
use App\Services\InscripcionCursoService;
use App\Services\PayPalService;
use Illuminate\Http\Request;

class CursoDetalleController extends Controller
{
  public function pago(Request $request)
  {
    $request->validate([
      'curso_id' => ['required', 'integer', 'exists:cursos,id'],
      'metodoPago' => ['required', 'in:pa,mp'],
    ]);

    // El importe siempre sale de la base, nunca del formulario
    $curso = Curso::findOrFail($request->curso_id);

    $datos = [
      'curso_id' => $curso->id,
      'unit_price' => PayPalService::importe($curso),
      'price' => InscripcionCursoService::precioEnPesos($curso),
      'description_name' => $curso->name,
      'description_title' => $curso->title,
    ];

    if ($request->metodoPago === 'pa') {
      return view('pages.pagoPayPal', ['datos' => $datos]);
    }

    return view('pages.pagoMercadoPago', ['datos' => $datos]);
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    $datosCurso = Curso::findOrFail($id);
    $temarios = CursoVideoUri::where('video_curso_id', $id)
      ->orderBy('nro_video_id', 'ASC')
      ->get(['id', 'video_curso_id', 'nro_video_id', 'descripcion', 'img_video', 'tipo_curso']);
    $moneda = Moneda::find($datosCurso->moneda_id);
    return view('pages.cursoDetalle', ['temarios' => $temarios, 'curso' => $datosCurso, 'moneda' => $moneda]);
  }
}
