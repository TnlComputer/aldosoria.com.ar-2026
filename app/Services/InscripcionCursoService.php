<?php

namespace App\Services;

use App\Mail\PagoExitosoMail;
use App\Models\Curso;
use App\Models\CursoPago;
use App\Models\CursoUsuario;
use App\Models\Moneda;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InscripcionCursoService
{
  public const FORMA_PAGO_PAYPAL = 3;
  public const FORMA_PAGO_MERCADOPAGO = 4;

  public const DIAS_ACCESO = 15;

  private const MONEDAS = [
    1 => 'ARS',
    2 => 'EUR',
    3 => 'USD',
  ];

  /**
   * Precio del curso en pesos segun la cotizacion cargada en administracion.
   */
  public static function precioEnPesos(Curso $curso): float
  {
    $codigo = self::MONEDAS[$curso->moneda_id] ?? 'EUR';
    $moneda = Moneda::where('moneda', $codigo)->first();

    if (!$moneda || !is_numeric($curso->coast)) {
      return 0;
    }

    return round($curso->coast * $moneda->cambio, 2);
  }

  /**
   * Registra la compra y habilita el curso al usuario.
   * Devuelve false si ese pago ya habia sido registrado.
   */
  public function otorgar(User $user, Curso $curso, int $formaPago, string $paymentId, ?string $orderId, float $monto, string $moneda): bool
  {
    $nuevo = DB::transaction(function () use ($user, $curso, $formaPago, $paymentId, $orderId, $monto, $moneda) {
      $existe = CursoUsuario::where('forma_pago_curso', $formaPago)
        ->where('payment_id', $paymentId)
        ->lockForUpdate()
        ->exists();

      if ($existe) {
        return false;
      }

      CursoUsuario::create([
        'curso_id' => $curso->id,
        'user_curso_id' => $user->id,
        'nombre_curso' => $curso->name,
        'titulo_curso' => $curso->title,
        'forma_pago_curso' => $formaPago,
        'payment_id' => $paymentId,
        'orderId' => $orderId,
        'pago_curso' => 'P',
        'amount' => $monto,
        'currency' => $moneda,
        'fecha_inscripcion' => Carbon::now()->toDateString(),
      ]);

      CursoPago::updateOrCreate(
        ['user_curso_id' => $user->id, 'video_curso_id' => $curso->id],
        ['vto_curso' => Carbon::now()->addDays(self::DIAS_ACCESO)]
      );

      return true;
    });

    if ($nuevo) {
      $this->notificar($user, $curso, $monto, $moneda);
    }

    return $nuevo;
  }

  private function notificar(User $user, Curso $curso, float $monto, string $moneda): void
  {
    $datos = [
      'curso' => trim($curso->name . ' - ' . $curso->title, ' -'),
      'monto' => $monto,
      'moneda' => $moneda,
      'fecha' => Carbon::now()->format('d-m-Y'),
      'cliente_email' => $user->email,
    ];

    // Un fallo del correo no debe anular un pago ya acreditado
    try {
      Mail::to($user->email)->send(new PagoExitosoMail($datos));
      Mail::to(config('mail.admin_address'))->send(new PagoExitosoMail($datos));
    } catch (\Throwable $e) {
      Log::error('No se pudo enviar el correo de pago: ' . $e->getMessage());
    }
  }
}
