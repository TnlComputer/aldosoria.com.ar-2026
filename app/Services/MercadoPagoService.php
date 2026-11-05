<?php

namespace App\Services;

use App\Models\Curso;
use App\Models\User;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Checkout Pro de MercadoPago via la API de Orders (/v1/orders).
 */
class MercadoPagoService
{
  public function __construct(private InscripcionCursoService $inscripciones)
  {
  }

  /**
   * Crea la order de pago con los datos del curso tomados de la base,
   * nunca de lo que envia el navegador. Devuelve el id de la order y la
   * URL de Mercado Pago a la que hay que redirigir al comprador.
   *
   * @return array{id: string, checkout_url: string}
   */
  public function crearOrden(User $user, Curso $curso): array
  {
    $monto = number_format(InscripcionCursoService::precioEnPesos($curso), 2, '.', '');

    $response = $this->api()
      ->withHeaders(['X-Idempotency-Key' => (string) Str::uuid()])
      ->post('/v1/orders', [
        'type' => 'online',
        'processing_mode' => 'manual',
        'total_amount' => $monto,
        'external_reference' => 'u' . $user->id . '-c' . $curso->id . '-' . Str::random(10),
        'description' => Str::limit($curso->name, 250),
        'items' => [
          [
            'external_code' => (string) $curso->id,
            'title' => Str::limit($curso->name, 250),
            'description' => Str::limit((string) $curso->title, 250),
            'quantity' => 1,
            'unit_price' => $monto,
          ],
        ],
        'payer' => [
          'email' => $user->email,
        ],
        'config' => [
          'online' => [
            'success_url' => route('mercadopago.success'),
            'failure_url' => route('mercadopago.failure'),
            'pending_url' => route('mercadopago.pending'),
            'auto_return' => 'approved',
          ],
        ],
      ]);

    if (!$response->successful() || !$response->json('id') || !$response->json('checkout_url')) {
      throw new RuntimeException('MercadoPago no creo la order (HTTP ' . $response->status() . '): ' . Str::limit($response->body(), 500));
    }

    return ['id' => (string) $response->json('id'), 'checkout_url' => (string) $response->json('checkout_url')];
  }

  /**
   * Consulta la order directamente en la API de MercadoPago.
   */
  public function obtenerOrden(string $orderId): ?array
  {
    if (!self::idValido($orderId)) {
      return null;
    }

    $response = $this->api()->get('/v1/orders/' . $orderId);

    if (!$response->successful()) {
      Log::warning('MercadoPago: no se pudo obtener la order ' . $orderId, ['status' => $response->status()]);
      return null;
    }

    return $response->json();
  }

  /**
   * Una order esta pagada cuando quedo procesada y acreditada.
   */
  public static function estaPagada(array $orden): bool
  {
    return ($orden['status'] ?? null) === 'processed'
      && ($orden['status_detail'] ?? null) === 'accredited';
  }

  /**
   * Una order sigue en curso: el comprador no termino de pagar o el pago esta en revision.
   */
  public static function estaPendiente(array $orden): bool
  {
    return in_array($orden['status'] ?? null, ['created', 'processing', 'action_required'], true);
  }

  /**
   * Verifica la order y, si esta pagada, habilita el curso.
   * Devuelve el usuario y curso acreditados, o null si el pago no es valido.
   */
  public function acreditar(string $orderId, ?User $usuarioEsperado = null): ?array
  {
    $orden = $this->obtenerOrden($orderId);

    if (!$orden || !self::estaPagada($orden)) {
      return null;
    }

    if (!preg_match('/^u(\d+)-c(\d+)-/', (string) ($orden['external_reference'] ?? ''), $ref)) {
      Log::warning('MercadoPago: referencia externa invalida', ['order_id' => $orderId]);
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

    // total_amount lo fijo el servidor al crear la order; se exige haberlo cobrado completo
    $esperado = (float) ($orden['total_amount'] ?? 0);
    $pagado = (float) ($orden['total_paid_amount'] ?? 0);

    if (($orden['currency'] ?? null) !== 'ARS' || $esperado <= 0 || $pagado + 0.01 < $esperado) {
      Log::warning('MercadoPago: el importe no coincide', ['order_id' => $orderId, 'pagado' => $pagado, 'esperado' => $esperado]);
      return null;
    }

    $paymentId = $this->pagoAcreditado($orden) ?? (string) $orden['id'];

    $this->inscripciones->otorgar($user, $curso, InscripcionCursoService::FORMA_PAGO_MERCADOPAGO, $paymentId, (string) $orden['id'], $pagado, 'AR$');

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

    // Los ids alfanumericos (como los de las orders) se firman en minusculas
    $manifiesto = 'id:' . strtolower($dataId) . ';request-id:' . $requestId . ';ts:' . $partes['ts'] . ';';

    return hash_equals(hash_hmac('sha256', $manifiesto, $secreto), $partes['v1']);
  }

  /**
   * Los ids de order son alfanumericos (ej. ORD01J49MMW3SSBK5PSV3DFR32959).
   */
  public static function idValido(string $id): bool
  {
    return (bool) preg_match('/^[A-Za-z0-9]{1,64}$/', $id);
  }

  private function pagoAcreditado(array $orden): ?string
  {
    foreach ($orden['transactions']['payments'] ?? [] as $pago) {
      if (($pago['status'] ?? null) === 'processed' && !empty($pago['id'])) {
        return (string) $pago['id'];
      }
    }

    return null;
  }

  private function api(): PendingRequest
  {
    return Http::withToken(config('services.mercadopago.access_token'))
      ->baseUrl(rtrim(config('services.mercadopago.base_url'), '/'))
      ->acceptJson()
      ->asJson()
      ->timeout(15);
  }
}
