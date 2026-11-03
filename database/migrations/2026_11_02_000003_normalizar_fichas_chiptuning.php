<?php

use App\Support\FichaChip;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  /**
   * Deja prolijas las fichas de chiptuning:
   * - mismas palabras para lo mismo (Gas Oil => Diesel, Automati => Automática...)
   * - potencias y torques con el mismo formato ("180HP Aprox" => "180 HP", "NM" => "Nm")
   * - textos cargados como potencia ("DPF OFF") pasan a la configuracion del Stage
   * No borra ninguna ficha.
   */
  public function up(): void
  {
    $campos = array_merge(FichaChip::POTENCIAS, FichaChip::TORQUES);

    foreach (DB::table('marcas_autos')->get() as $ficha) {
      $datos = FichaChip::normalizar((array) $ficha);

      // Un Stage cuya "potencia" no es una medida es en realidad una descripcion
      foreach ([1, 2, 3] as $n) {
        foreach (['stage' . $n, 'torque_stg' . $n] as $campo) {
          $valor = $datos[$campo] ?? '';
          if ($valor !== '' && !FichaChip::esMedida($valor)) {
            $config = (string) ($datos['configuracion_stg' . $n] ?? '');
            if (stripos($config, $valor) === false) {
              $datos['configuracion_stg' . $n] = trim($valor . ($config !== '' ? '. ' . $config : ''));
            }
            $datos[$campo] = '';
          }
        }
      }

      $cambios = array_diff_assoc(array_map(fn ($v) => (string) $v, $datos), array_map(fn ($v) => (string) $v, (array) $ficha));
      unset($cambios['id'], $cambios['created_at'], $cambios['updated_at']);

      if ($cambios) {
        DB::table('marcas_autos')->where('id', $ficha->id)->update($cambios + ['updated_at' => now()]);
      }
    }
  }

  public function down(): void
  {
    // Correccion de datos: para volver atras usar el respaldo de la tabla
  }
};
