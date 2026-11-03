<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Marcas_autos extends Model
{
  use HasFactory;

  public $guarded = [];

  public const CARPETA_GRAFICOS = 'black/img/Graficos_Banco_Pruebas';

  public function marcas_images_logos(): BelongsToMany
  {
    return $this->belongsToMany(Marcas_images_autos::class);
  }

  /**
   * Marcas que tienen fichas cargadas, con su logo y cantidad, ordenadas por nombre.
   *
   * @return \Illuminate\Support\Collection<int, array{nombre: string, total: int, logo: ?string}>
   */
  public static function marcasConFichas(): Collection
  {
    $logos = Marcas_images_autos::pluck('foto', 'marca');

    return self::selectRaw('marca, count(*) total')
      ->groupBy('marca')
      ->orderBy('marca')
      ->pluck('total', 'marca')
      ->map(fn ($total, $marca) => [
        'nombre' => $marca,
        'total' => (int) $total,
        'logo' => !empty($logos[$marca]) ? asset('black/img/Marcas/' . $logos[$marca]) : null,
      ])
      ->values();
  }

  /** Primer numero de un texto: "122-125 HP" => 122 */
  private static function numero(?string $texto): ?int
  {
    return preg_match('/\d+/', (string) $texto, $m) ? (int) $m[0] : null;
  }

  /**
   * Original y cada Stage cargado, con la ganancia sobre el original.
   *
   * @return array<int, array{nombre: string, potencia: ?string, torque: ?string, ganancia_hp: ?int, ganancia_pct: ?int, ganancia_nm: ?int, configuracion: ?string, grafico: ?string}>
   */
  public function etapas(): array
  {
    $hpOriginal = self::numero($this->potencia_original);
    $nmOriginal = self::numero($this->torque_original);

    $etapas = [[
      'nombre' => 'Original',
      'potencia' => trim((string) $this->potencia_original) ?: null,
      'torque' => trim((string) $this->torque_original) ?: null,
      'ganancia_hp' => null,
      'ganancia_pct' => null,
      'ganancia_nm' => null,
      'configuracion' => null,
      'grafico' => null,
    ]];

    foreach ([1, 2, 3] as $n) {
      $potencia = trim((string) $this->{'stage' . $n});
      $torque = trim((string) $this->{'torque_stg' . $n});
      $configuracion = trim((string) $this->{'configuracion_stg' . $n});

      // Un Stage sin potencia pero con descripcion (ej.: "Limitador de velocidad liberado") tambien se muestra
      if ($potencia === '' && $torque === '' && ($n > 1 || $configuracion === '')) {
        continue;
      }

      $hp = self::numero($potencia);
      $nm = self::numero($torque);
      $imagen = basename((string) $this->{'img_st' . $n});

      $etapas[] = [
        'nombre' => 'Stage ' . $n,
        'potencia' => $potencia ?: null,
        'torque' => $torque ?: null,
        'ganancia_hp' => $hp && $hpOriginal ? $hp - $hpOriginal : null,
        'ganancia_pct' => $hp && $hpOriginal ? (int) round(($hp - $hpOriginal) * 100 / $hpOriginal) : null,
        'ganancia_nm' => $nm && $nmOriginal ? $nm - $nmOriginal : null,
        'configuracion' => $configuracion ?: null,
        'grafico' => $imagen !== '' && is_file(public_path(self::CARPETA_GRAFICOS . '/' . $imagen))
          ? asset(self::CARPETA_GRAFICOS . '/' . $imagen)
          : null,
      ];
    }

    return $etapas;
  }

  /** Motor, salvo que repita el nombre del modelo ("Mito" / "Mito") */
  public function motorDistinto(): ?string
  {
    $motor = trim((string) $this->motor);

    return $motor !== '' && mb_strtolower($motor) !== mb_strtolower(trim((string) $this->modelo)) ? $motor : null;
  }

  /** Mejor potencia lograda, para mostrar en los listados */
  public function mejorStage(): ?array
  {
    $etapas = $this->etapas();

    return count($etapas) > 1 ? end($etapas) : null;
  }
}
