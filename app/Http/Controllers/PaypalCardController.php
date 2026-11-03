<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaypalCardController extends Controller
{
  public function __construct(private PayPalService $paypal)
  {
  }

  public function create(Request $request)
  {
    $datos = $request->validate([
      'curso_id' => ['required', 'integer', 'exists:cursos,id'],
    ]);

    try {
      $orderId = $this->paypal->crearOrden(Auth::user(), Curso::findOrFail($datos['curso_id']));

      return response()->json(['id' => $orderId]);
    } catch (\Throwable $e) {
      Log::error('Error al crear la orden de PayPal: ' . $e->getMessage());

      return response()->json(['error' => 'No se pudo iniciar el pago con PayPal'], 500);
    }
  }

  public function process(Request $request)
  {
    $datos = $request->validate([
      'orderID' => ['required', 'string', 'max:40'],
      'curso_id' => ['required', 'integer', 'exists:cursos,id'],
    ]);

    try {
      $ok = $this->paypal->capturar(Auth::user(), Curso::findOrFail($datos['curso_id']), $datos['orderID']);
    } catch (\Throwable $e) {
      Log::error('Error al capturar el pago de PayPal: ' . $e->getMessage());
      $ok = false;
    }

    if (!$ok) {
      return response()->json(['error' => 'El pago no pudo ser verificado'], 422);
    }

    return response()->json(['mensaje' => 'Pago procesado correctamente']);
  }
}
