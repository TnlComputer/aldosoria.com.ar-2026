<?php

namespace App\Services;

use App\Models\Curso;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoService
{
  public function __construct(private InscripcionCursoService $inscripciones)
  {
  }

  /**
   * Crea la preferencia de pago con los datos del curso tomados de la base,
   * nunca de lo que envia el navegador.
   */
  public function crearPreferencia(User $user, Curso $curso): string
  {
    MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

    $monto = InscripcionCursoService::precioEnPesos($curso);

    $preference = (new PreferenceClient())->create([
      'external_reference' => 'u' . $user->id . '-c' . $curso->id . '-' . Str::random(10),
      'items' => [
        [
          'id' => (string) $curso->id,
          'title' => Str::limit($curso->name, 250),
          'description' => Str::limit((string) $curso->title, 250),
          'quantity' => 1,
          'currency_id' => 'ARS',
          'unit_price' => $monto,
        ],
      ],
      'payer' => [
        'email' => $user->email,
      ],
      'metadata' => [
        'user_id' => $user->id,
        'curso_id' => $curso->id,
        'monto' => $monto,
      ],
      'notification_url' => route('mercadopago.payment.webhook'),
      'back_urls' => [
        'success' => route('mercadopago.success'),
        'failure' => route('mercadopago.failure'),
        'pending' => route('mercadopago.pending'),
      ],
      'auto_return' => 'approved',
      'purpose' => 'wallet_purchase',
    ]);

    return $preference->id;
  }

  /**
   * Consulta el pago directamente en la API de MercadoPago.
   */
  public function obtenerPago(string $paymentId): ?array
  {
    if (!ctype_digit($paymentId)) {
      return null;
    }

    $response = Http::withToken(config('services.mercadopago.access_token'))
      ->timeout(15)
      ->get(rtrim(config('services.mercadopago.base_url'), '/') . '/v1/payments/' . $paymentId);

    if (!$response->successful()) {
      Log::warning('MercadoPago: no se pudo obtener el pago ' . $paymentId, ['status' => $response->status()]);
      return null;
    }

    return $response->json();
  }

  /**
   * Verifica el pago y, si esta aprobado, habilita el curso.
   * Devuelve el usuario y curso acreditados, o null si el pago no es valido.
   */
  public function acreditar(string $paymentId, ?User $usuarioEsperado = null): ?array
  {
    $pago = $this->obtenerPago($paymentId);

    if (!$pago || ($pago['status'] ?? null) !== 'approved') {
      return null;
    }

    if (!preg_match('/^u(\d+)-c(\d+)-/', (string) ($pago['external_reference'] ?? ''), $ref)) {
      Log::warning('MercadoPago: referencia externa invalida', ['payment_id' => $paymentId]);
      return null;
    }

    [$userId, $cursoId] = [(int) $ref[1], (int) $ref[2]];

    if ($usuarioEsperado && $usuarioEsperado->id !== $userId) {
      return null;
    }

    $user = User::find($userId);
    $curso = Curso::find($cursoId);

    if (!$user || !$curso) {
      return null;
    }

    $esperado = (float) ($pago['metadata']['monto'] ?? 0);
    $pagado = (float) ($pago['transaction_amount'] ?? 0);

    if (($pago['currency_id'] ?? null) !== 'ARS' || $esperado <= 0 || $pagado + 0.01 < $esperado) {
      Log::warning('MercadoPago: el importe no coincide', ['payment_id' => $paymentId, 'pagado' => $pagado, 'esperado' => $esperado]);
      return null;
    }

    $orderId = isset($pago['order']['id']) ? (string) $pago['order']['id'] : null;

    $this->inscripciones->otorgar($user, $curso, InscripcionCursoService::FORMA_PAGO_MERCADOPAGO, (string) $pago['id'], $orderId, $pagado, 'AR$');

    return ['user' => $user, 'curso' => $curso];
  }

  /**
   * Valida la firma x-signature de las notificaciones, si hay clave configurada.
   */
  public function firmaValida(?string $firma, ?string $requestId, string $dataId): bool
  {
    $secreto = config('services.mercadopago.webhook_secret');

    if (!$secreto) {
      return true;
    }

    if (!$firma) {
      return false;
    }

    $partes = [];
    foreach (explode(',', $firma) as $parte) {
      [$clave, $valor] = array_pad(explode('=', trim($parte), 2), 2, null);
      $partes[$clave] = $valor;
    }

    if (empty($partes['ts']) || empty($partes['v1'])) {
      return false;
    }

    $manifiesto = 'id:' . strtolower($dataId) . ';request-id:' . $requestId . ';ts:' . $partes['ts'] . ';';

    return hash_equals(hash_hmac('sha256', $manifiesto, $secreto), $partes['v1']);
  }
}
