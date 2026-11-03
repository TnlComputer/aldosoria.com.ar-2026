<?php

use App\Support\FichaChip;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  /**
   * Repara acentos guardados con la codificacion equivocada ("MÃ¡xima" => "Máxima")
   * en los textos que se muestran en el sitio.
   */
  public function up(): void
  {
    $columnas = [
      'novedades' => ['novedades'],
      'cursos' => ['name', 'title'],
      'cursos_videos_uris' => ['descripcion', 'nombre_curso', 'nombre_video'],
      'cursos_usuarios' => ['nombre_curso', 'titulo_curso'],
    ];

    foreach ($columnas as $tabla => $campos) {
      foreach (DB::table($tabla)->get(array_merge(['id'], $campos)) as $fila) {
        $cambios = [];
        foreach ($campos as $campo) {
          $valor = (string) $fila->$campo;
          $reparado = FichaChip::arreglarAcentos($valor);
          if ($reparado !== $valor) {
            $cambios[$campo] = $reparado;
          }
        }
        if ($cambios) {
          DB::table($tabla)->where('id', $fila->id)->update($cambios);
        }
      }
    }
  }

  public function down(): void
  {
    //
  }
};
