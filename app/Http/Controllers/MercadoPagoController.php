<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MercadoPagoController extends Controller
{
  public function __construct(private MercadoPagoService $mercadoPago)
  {
  }

  public function createPreference(Request $request)
  {
    $datos = $request->validate([
      'curso_id' => ['required', 'integer', 'exists:cursos,id'],
    ]);

    try {
      $preferenceId = $this->mercadoPago->crearPreferencia(Auth::user(), Curso::findOrFail($datos['curso_id']));

      return response()->json(['preference_id' => $preferenceId]);
    } catch (\Throwable $e) {
      Log::error('Error al crear la preferencia de Mercado Pago: ' . $e->getMessage());

      return response()->json(['error' => 'Error al crear la preferencia de Mercado Pago'], 500);
    }
  }

  /**
   * Notificaciones de MercadoPago. El pago siempre se verifica contra su API.
   */
  public function webhook(Request $request)
  {
    $tipo = $request->input('type', $request->input('topic'));
    $paymentId = (string) $request->input('data.id', $request->input('id', ''));

    if ($tipo !== 'payment' || !ctype_digit($paymentId)) {
      return response()->json(['message' => 'ignorado']);
    }

    if (!$this->mercadoPago->firmaValida($request->header('x-signature'), $request->header('x-request-id'), $paymentId)) {
      Log::warning('MercadoPago: notificacion con firma invalida', ['payment_id' => $paymentId, 'ip' => $request->ip()]);
      return response()->json(['error' => 'firma invalida'], 401);
    }

    try {
      $this->mercadoPago->acreditar($paymentId);
    } catch (\Throwable $e) {
      Log::error('Error en el webhook de Mercado Pago: ' . $e->getMessage());
      return response()->json(['error' => 'Error en el webhook de Mercado Pago'], 500);
    }

    return response()->json(['message' => 'Webhook recibido']);
  }

  public function success(Request $request)
  {
    $paymentId = (string) $request->query('payment_id', '');

    if (!ctype_digit($paymentId)) {
      return view('pages.failure');
    }

    try {
      if ($this->mercadoPago->acreditar($paymentId, Auth::user())) {
        return view('pages.thanks');
      }

      $estado = $this->mercadoPago->obtenerPago($paymentId)['status'] ?? null;
    } catch (\Throwable $e) {
      Log::error('Error al verificar el pago de Mercado Pago: ' . $e->getMessage());
      $estado = null;
    }

    return in_array($estado, ['pending', 'in_process', 'authorized'], true)
      ? view('pages.pending')
      : view('pages.failure');
  }

  public function failure()
  {
    return view('pages.failure');
  }

  public function pending()
  {
    return view('pages.pending');
  }
}
