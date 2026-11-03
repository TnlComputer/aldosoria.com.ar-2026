<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  /**
   * El codigo anterior podia guardar la ruta temporal de la subida ("/tmp/phpYihZkH")
   * en lugar del nombre del archivo. Esos valores nunca apuntaron a un archivo real:
   * se vacian para que el admin los muestre como "Sin contenido" y se vuelvan a subir.
   */
  public function up(): void
  {
    foreach (['archivo1_curso', 'archivo2_curso'] as $campo) {
      DB::table('cursos_videos_uris')
        ->where(fn ($q) => $q->where($campo, 'like', '/%')->orWhere($campo, 'like', '%\\\\%')->orWhere($campo, 'like', '%:%'))
        ->update([$campo => null]);
    }
  }

  public function down(): void
  {
    //
  }
};
