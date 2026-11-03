<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/**
 * Defensas contra registros y mensajes automaticos:
 * campo trampa, tiempo minimo de llenado y Cloudflare Turnstile (opcional).
 */
class AntiBot
{
  public const CAMPO_TRAMPA = 'sitio_web';
  public const CAMPO_TIEMPO = '_ft';

  /** Ninguna persona completa un formulario en menos de este tiempo */
  private const SEGUNDOS_MINIMOS = 3;

  /** Un formulario abierto hace mas de un dia se considera vencido */
  private const SEGUNDOS_MAXIMOS = 86400;

  public static function token(): string
  {
    return Crypt::encryptString((string) now()->getTimestamp());
  }

  public static function turnstileActivo(): bool
  {
    return filled(config('services.turnstile.site_key')) && filled(config('services.turnstile.secret'));
  }

  /**
   * Corta la peticion si parece automatica. Devuelve normalmente si es una persona.
   */
  public static function verificar(Request $request, bool $controlarTiempo = true): void
  {
    if ($request->filled(self::CAMPO_TRAMPA)) {
      self::rechazar($request, 'campo trampa completo');
    }

    if ($controlarTiempo) {
      try {
        $inicio = (int) Crypt::decryptString((string) $request->input(self::CAMPO_TIEMPO));
      } catch (\Throwable) {
        self::rechazar($request, 'sin marca de tiempo');
      }

      $demora = now()->getTimestamp() - $inicio;
      if ($demora < self::SEGUNDOS_MINIMOS || $demora > self::SEGUNDOS_MAXIMOS) {
        self::rechazar($request, "tiempo de llenado {$demora}s");
      }
    }

    if (self::turnstileActivo()) {
      $respuesta = Http::asForm()->timeout(10)->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
        'secret' => config('services.turnstile.secret'),
        'response' => (string) $request->input('cf-turnstile-response'),
        'remoteip' => $request->ip(),
      ]);

      if (!$respuesta->json('success')) {
        self::rechazar($request, 'turnstile no superado');
      }
    }
  }

  private static function rechazar(Request $request, string $motivo): never
  {
    Log::warning('AntiBot: envio rechazado (' . $motivo . ')', ['ip' => $request->ip(), 'ruta' => $request->path()]);

    throw ValidationException::withMessages([
      'antibot' => 'No pudimos procesar el formulario. Esperá unos segundos y volvé a intentarlo.',
    ]);
  }
}
