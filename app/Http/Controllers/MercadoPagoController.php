<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MercadoPagoController extends Controller
{
  /** Clave de sesion con la ultima order creada por el alumno. */
  private const SESION_ORDEN = 'mercadopago_order_id';

  public function __construct(private MercadoPagoService $mercadoPago)
  {
  }

  /**
   * Crea la order en MercadoPago y devuelve la URL de pago a la que se redirige al alumno.
   */
  public function createOrder(Request $request)
  {
    $datos = $request->validate([
      'curso_id' => ['required', 'integer', 'exists:cursos,id'],
    ]);

    try {
      $orden = $this->mercadoPago->crearOrden(Auth::user(), Curso::findOrFail($datos['curso_id']));
    } catch (\Throwable $e) {
      Log::error('Error al crear la order de Mercado Pago: ' . $e->getMessage());

      return response()->json(['error' => 'No se pudo iniciar el pago con Mercado Pago'], 500);
    }

    // Al volver de MercadoPago se verifica esta order aunque no llegue en la URL
    $request->session()->put(self::SESION_ORDEN, $orden['id']);

    return response()->json(['checkout_url' => $orden['checkout_url']]);
  }

  /**
   * Notificaciones de MercadoPago (evento "Order"). La order siempre se verifica contra su API.
   */
  public function webhook(Request $request)
  {
    $tipo = $request->input('type', $request->query('topic'));
    // data.id llega en el cuerpo JSON y/o en la URL (PHP convierte "data.id" en "data_id")
    $orderId = (string) $request->input('data.id', $request->query('data_id', ''));

    if ($tipo !== 'order' || !MercadoPagoService::idValido($orderId)) {
      return response()->json(['message' => 'ignorado']);
    }

    if (!$this->mercadoPago->firmaValida($request->header('x-signature'), $request->header('x-request-id'), $orderId)) {
      Log::warning('MercadoPago: notificacion con firma invalida', ['order_id' => $orderId, 'ip' => $request->ip()]);
      return response()->json(['error' => 'firma invalida'], 401);
    }

    try {
      $this->mercadoPago->acreditar($orderId);
    } catch (\Throwable $e) {
      Log::error('Error en el webhook de Mercado Pago: ' . $e->getMessage());
      return response()->json(['error' => 'Error en el webhook de Mercado Pago'], 500);
    }

    return response()->json(['message' => 'Webhook recibido']);
  }

  public function success(Request $request)
  {
    $orderId = (string) $request->query('order_id', $request->session()->get(self::SESION_ORDEN, ''));

    if (!MercadoPagoService::idValido($orderId)) {
      return view('pages.failure');
    }

    try {
      if ($this->mercadoPago->acreditar($orderId, Auth::user())) {
        $request->session()->forget(self::SESION_ORDEN);
        return view('pages.thanks');
      }

      $orden = $this->mercadoPago->obtenerOrden($orderId);
    } catch (\Throwable $e) {
      Log::error('Error al verificar el pago de Mercado Pago: ' . $e->getMessage());
      $orden = null;
    }

    return $orden && MercadoPagoService::estaPendiente($orden)
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
