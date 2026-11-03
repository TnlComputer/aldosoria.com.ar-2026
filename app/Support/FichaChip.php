<?php

namespace App\Support;

/**
 * Deja prolijos los datos de una ficha de chiptuning:
 * mismas palabras para lo mismo, unidades con el mismo formato y sin espacios de mas.
 */
class FichaChip
{
  public const POTENCIAS = ['potencia_original', 'stage1', 'stage2', 'stage3'];

  public const TORQUES = ['torque_original', 'torque_stg1', 'torque_stg2', 'torque_stg3'];

  /** Cada patron abarca el texto completo */
  private const TRANSMISIONES = [
    '/^autom.*$/iu' => 'Automática',
    '/^manual.*$/iu' => 'Manual',
    '/^dsg$/iu' => 'DSG',
    '/^dq\s*(\d+).*$/iu' => 'DSG DQ$1',
    '/^zf\s*(\d+)\s*mar.*$/iu' => 'Automática ZF $1 marchas',
    '/^(variable|cvt).*$/iu' => 'CVT',
  ];

  private const COMBUSTIBLES = [
    '/^(gas\s*oil|diesel|diésel|gasoil).*$/iu' => 'Diesel',
    '/^(gasolina|nafta).*$/iu' => 'Gasolina',
    '/^gnc.*$/iu' => 'GNC',
  ];

  /** Errores de escritura frecuentes en las configuraciones de cada Stage */
  private const CORRECCIONES = [
    '/\bDow+n?pipe\b/iu' => 'Downpipe',
    '/\bEscapeCompleto\b/iu' => 'Escape completo',
    '/\b[CK]onico\b/iu' => 'Cónico',
    '/\bKIt\b/u' => 'Kit',
    '/\bKN\b/u' => 'K&N',
    '/\bOem\b/iu' => 'OEM',
    '/\bMultiple\b/u' => 'Múltiple',
    '/\bModificacion\b/iu' => 'Modificación',
    '/\bProgramacion\b/iu' => 'Programación',
    '/\bGeometria\b/iu' => 'Geometría',
    '/\bArbol\b/u' => 'Árbol',
    '/\bBujias\b/iu' => 'Bujías',
    '/\bValvulas\b/iu' => 'Válvulas',
    '/(\d)\s*mm\b/iu' => '$1 mm',
    '/\s*,\s*/u' => ', ',
    '/\s*\/\s*/u' => ' / ',
    '/\s*\+\s*/u' => ' + ',
    '/\s+-\s*/u' => ' - ',
  ];

  /**
   * Repara acentos guardados con la codificacion equivocada ("CÃ³nico" => "Cónico").
   */
  public static function arreglarAcentos(string $texto): string
  {
    if (!preg_match('/Ã.|Â./u', $texto)) {
      return $texto;
    }

    $reparado = mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8');

    return mb_check_encoding($reparado, 'UTF-8') ? $reparado : $texto;
  }

  public static function textoConfiguracion(string $texto): string
  {
    return trim(preg_replace(array_keys(self::CORRECCIONES), array_values(self::CORRECCIONES), $texto), " ,-/");
  }

  public static function normalizar(array $datos): array
  {
    foreach ($datos as $campo => $valor) {
      if (!is_string($valor)) {
        continue;
      }
      // Los nombres de archivo se respetan tal cual estan en el disco
      $datos[$campo] = str_starts_with($campo, 'img_')
        ? trim($valor)
        : trim(preg_replace('/\s+/u', ' ', self::arreglarAcentos($valor)));
    }

    foreach ([1, 2, 3] as $n) {
      if (isset($datos['configuracion_stg' . $n])) {
        $datos['configuracion_stg' . $n] = self::textoConfiguracion($datos['configuracion_stg' . $n]);
      }
    }

    if (isset($datos['transmicion'])) {
      $datos['transmicion'] = self::reemplazar($datos['transmicion'], self::TRANSMISIONES);
    }

    if (isset($datos['combustible'])) {
      $datos['combustible'] = self::reemplazar($datos['combustible'], self::COMBUSTIBLES);
    }

    foreach (self::POTENCIAS as $campo) {
      if (isset($datos[$campo])) {
        $datos[$campo] = self::medida($datos[$campo], 'HP');
      }
    }

    foreach (self::TORQUES as $campo) {
      if (isset($datos[$campo])) {
        $datos[$campo] = self::medida($datos[$campo], 'Nm');
      }
    }

    return $datos;
  }

  private static function reemplazar(string $valor, array $reglas): string
  {
    foreach ($reglas as $patron => $reemplazo) {
      if (preg_match($patron, $valor)) {
        return preg_replace($patron, $reemplazo, $valor);
      }
    }

    return $valor;
  }

  /**
   * "180HP Aprox" => "180 HP", "275-285 NM" => "275-285 Nm", "0 NM" => "".
   * Si no es una medida (por ejemplo "DPF OFF") se devuelve tal cual.
   */
  public static function medida(string $valor, string $unidad): string
  {
    if ($valor === '') {
      return '';
    }

    if (!preg_match('/^(\d+)(?:\s*-\s*(\d+))?\s*(hp|cv|nm)?\b/i', $valor, $m)) {
      return $valor;
    }

    if ((int) $m[1] === 0 && empty($m[2])) {
      return '';
    }

    return $m[1] . (!empty($m[2]) ? '-' . $m[2] : '') . ' ' . $unidad;
  }

  /** Es una medida valida (numero con o sin unidad) */
  public static function esMedida(?string $valor): bool
  {
    return (bool) preg_match('/^\d+(\s*-\s*\d+)?\s*(hp|cv|nm)?\b/i', (string) $valor);
  }
}
