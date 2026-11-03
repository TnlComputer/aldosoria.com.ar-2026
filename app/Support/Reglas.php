<?php

namespace App\Support;

/**
 * Reglas de validacion para los datos personales que cargan los usuarios.
 * Solo aceptan texto de personas: nada de codigo, enlaces ni simbolos raros.
 */
class Reglas
{
  /** Letras (con acentos y ñ), espacios, apostrofe, punto y guion */
  private const NOMBRE = "/^\p{L}[\p{L}\p{M} .'\-]*$/u";

  private const TELEFONO = '/^\+?[0-9 ()\-.]{6,25}$/';

  public static function nombre(bool $obligatorio = true): array
  {
    // Ademas: nada de dominios ("spam.com") ni "www"
    return [$obligatorio ? 'required' : 'nullable', 'string', 'min:2', 'max:50', 'regex:' . self::NOMBRE, 'not_regex:/\.\p{L}|www/iu'];
  }

  public static function telefono(bool $obligatorio = true): array
  {
    return [$obligatorio ? 'required' : 'nullable', 'string', 'max:30', 'regex:' . self::TELEFONO];
  }

  /** El email se pasa a minusculas antes de validar (ver email()), asi se acepta escrito en mayusculas */
  public static function email(): array
  {
    return ['required', 'string', 'email:rfc,strict', 'max:150'];
  }

  public static function normalizarEmail(?string $email): string
  {
    return mb_strtolower((string) self::limpiar($email));
  }

  public static function siNo(): array
  {
    return ['nullable', 'in:SI,NO'];
  }

  /**
   * Patrones de los bots de spam vistos en la base:
   * - el apellido es el nombre con dos mayusculas al final ("MichaelDet" / "MichaelDetTH")
   * - nombre y apellido identicos
   * - cadenas al azar con mayusculas mezcladas ("ySkDjmWUEyZrTQmYgQ")
   */
  public static function nombreDeBot(?string $nombre, ?string $apellido): bool
  {
    $nombre = trim((string) $nombre);
    $apellido = trim((string) $apellido);

    if ($nombre === '' || $apellido === '') {
      return false;
    }

    return preg_match('/^' . preg_quote($nombre, '/') . '[A-Z]{2}$/u', $apellido) === 1
      || (mb_strlen($nombre) > 3 && mb_strtolower($nombre) === mb_strtolower($apellido))
      || self::mayusculasAlAzar($nombre)
      || self::mayusculasAlAzar($apellido);
  }

  /** Una palabra con minusculas y 3 o mas mayusculas internas no es un nombre real */
  private static function mayusculasAlAzar(string $texto): bool
  {
    foreach (preg_split('/[\s\-]+/u', $texto) as $palabra) {
      $resto = mb_substr($palabra, 1);
      if (preg_match('/\p{Ll}/u', $palabra) && preg_match_all('/\p{Lu}/u', $resto) >= 3) {
        return true;
      }
    }

    return false;
  }

  /** Quita espacios repetidos y caracteres invisibles */
  public static function limpiar(?string $texto): ?string
  {
    if ($texto === null) {
      return null;
    }

    $texto = preg_replace('/[\p{C}]+/u', '', $texto);

    return trim(preg_replace('/\s+/u', ' ', $texto));
  }
}
