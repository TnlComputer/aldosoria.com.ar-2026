<?php

namespace App\Services;

use App\Models\Curso;
use App\Models\User;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PayPalService
{
  public function __construct(private InscripcionCursoService $inscripciones)
  {
  }

  private function baseUrl(): string
  {
    return config('services.paypal.mode') === 'sandbox'
      ? 'https://api-m.sandbox.paypal.com'
      : 'https://api-m.paypal.com';
  }

  private function cliente(): PendingRequest
  {
    $token = Http::asForm()
      ->withBasicAuth(config('services.paypal.client_id'), config('services.paypal.client_secret'))
      ->timeout(15)
      ->post($this->baseUrl() . '/v1/oauth2/token', ['grant_type' => 'client_credentials'])
      ->throw()
      ->json('access_token');

    return Http::withToken($token)->acceptJson()->timeout(20)->baseUrl($this->baseUrl());
  }

  public static function importe(Curso $curso): string
  {
    return number_format((float) $curso->coast, 2, '.', '');
  }

  private static function referencia(User $user, Curso $curso): string
  {
    return 'u' . $user->id . '-c' . $curso->id;
  }

  /**
   * Crea la orden en PayPal con el importe tomado de la base.
   */
  public function crearOrden(User $user, Curso $curso): string
  {
    $orden = $this->cliente()->post('/v2/checkout/orders', [
      'intent' => 'CAPTURE',
      'purchase_units' => [[
        'reference_id' => (string) $curso->id,
        'custom_id' => self::referencia($user, $curso),
        'description' => mb_substr($curso->name . ' - ' . $curso->title, 0, 127),
        'amount' => [
          'currency_code' => 'EUR',
          'value' => self::importe($curso),
        ],
      ]],
    ])->throw()->json();

    return $orden['id'];
  }

  /**
   * Captura la orden, comprueba importe y referencia, y habilita el curso.
   */
  public function capturar(User $user, Curso $curso, string $orderId): bool
  {
    if (!preg_match('/^[A-Z0-9]{5,40}$/', $orderId)) {
      return false;
    }

    $cliente = $this->cliente();

    $orden = $cliente->get('/v2/checkout/orders/' . $orderId)->throw()->json();

    if (($orden['status'] ?? null) === 'APPROVED') {
      $orden = $cliente->withBody('{}', 'application/json')
        ->post('/v2/checkout/orders/' . $orderId . '/capture')
        ->throw()
        ->json();
    }

    if (($orden['status'] ?? null) !== 'COMPLETED') {
      return false;
    }

    $unidad = $orden['purchase_units'][0] ?? [];
    $captura = $unidad['payments']['captures'][0] ?? null;

    if (!$captura || ($captura['status'] ?? null) !== 'COMPLETED') {
      return false;
    }

    $referenciaOk = ($unidad['custom_id'] ?? $captura['custom_id'] ?? null) === self::referencia($user, $curso);
    $monedaOk = ($captura['amount']['currency_code'] ?? null) === 'EUR';
    $importeOk = (float) ($captura['amount']['value'] ?? 0) + 0.001 >= (float) self::importe($curso);

    if (!$referenciaOk || !$monedaOk || !$importeOk) {
      Log::warning('PayPal: la orden no coincide con el curso', ['order_id' => $orderId, 'user_id' => $user->id, 'curso_id' => $curso->id]);
      return false;
    }

    $this->inscripciones->otorgar(
      $user,
      $curso,
      InscripcionCursoService::FORMA_PAGO_PAYPAL,
      (string) $captura['id'],
      $orderId,
      (float) $captura['amount']['value'],
      'EUR'
    );

    return true;
  }
}
