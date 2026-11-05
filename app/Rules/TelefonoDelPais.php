<?php

namespace App\Rules;

use App\Models\Pais;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberType;
use libphonenumber\PhoneNumberUtil;

/**
 * El telefono tiene que ser un numero valido del pais elegido.
 * Rechaza numeros inventados y numeros de otro pais (ej.: +56 con Argentina elegida).
 */
class TelefonoDelPais implements ValidationRule
{
  public function __construct(private mixed $paisId)
  {
  }

  public function validate(string $attribute, mixed $value, Closure $fail): void
  {
    $pais = is_numeric($this->paisId) ? Pais::find((int) $this->paisId) : null;
    if (!$pais || (int) $pais->id === Pais::SIN_PAIS || !$pais->codigo_2) {
      return; // el error lo informa la validacion del pais
    }

    $util = PhoneNumberUtil::getInstance();
    $ejemplo = self::ejemplo($pais->codigo_2);

    try {
      $numero = $util->parse((string) $value, $pais->codigo_2);
    } catch (NumberParseException) {
      $fail("El teléfono no es válido para {$pais->nombre}." . ($ejemplo ? " Ejemplo: {$ejemplo}." : ''));
      return;
    }

    $region = $util->getRegionCodeForNumber($numero);
    if ($region && $region !== $pais->codigo_2) {
      $otro = Pais::where('codigo_2', $region)->value('nombre') ?? $region;
      $fail("Ese teléfono es de {$otro}, pero elegiste {$pais->nombre}. Corregí el país o el número.");
      return;
    }

    if (!$util->isValidNumberForRegion($numero, $pais->codigo_2)) {
      $fail("El teléfono no es válido para {$pais->nombre}. Revisá el código de área." . ($ejemplo ? " Ejemplo: {$ejemplo}." : ''));
    }
  }

  /** Numero en formato internacional (+54 11 5824-5077) para guardarlo siempre igual */
  public static function normalizar(string $telefono, ?string $iso): string
  {
    try {
      $util = PhoneNumberUtil::getInstance();
      $numero = $util->parse($telefono, $iso ?: 'AR');

      return $util->isValidNumber($numero) ? $util->format($numero, PhoneNumberFormat::INTERNATIONAL) : $telefono;
    } catch (NumberParseException) {
      return $telefono;
    }
  }

  /** Ejemplo de celular del pais, para orientar al usuario */
  public static function ejemplo(string $iso): ?string
  {
    $util = PhoneNumberUtil::getInstance();
    $numero = $util->getExampleNumberForType($iso, PhoneNumberType::MOBILE) ?? $util->getExampleNumber($iso);

    return $numero ? $util->format($numero, PhoneNumberFormat::INTERNATIONAL) : null;
  }
}
