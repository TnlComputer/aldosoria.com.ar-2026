<?php

namespace App\Http\Controllers;

use App\Models\CursoPago;
use App\Models\CursoVideoUri;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CursoPagoController extends Controller
{
  /**
   * Solo el comprador del curso (o un administrador) puede ver su contenido,
   * y unicamente hasta la fecha de vencimiento.
   */
  private function autorizar(int $cursoId): void
  {
    $user = Auth::user();

    if ($user->isAdmin()) {
      return;
    }

    $pago = CursoPago::where('user_curso_id', $user->id)
      ->where('video_curso_id', $cursoId)
      ->orderByDesc('vto_curso')
      ->first();

    abort_unless($pago, 403);

    if (Carbon::parse($pago->vto_curso)->endOfDay()->isPast()) {
      abort(403, 'El acceso a este curso venció el ' . Carbon::parse($pago->vto_curso)->format('d-m-Y') . '. Para renovarlo escribinos a ' . config('mail.admin_address'));
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    abort_unless(ctype_digit($id), 404);
    $this->autorizar((int) $id);

    $cursoPago = CursoVideoUri::where('video_curso_id', $id)
      ->orderBy('nro_video_id', 'asc')
      ->paginate(4);

    return view('pages.cursosPagos', ['cursoPago' => $cursoPago, 'titulos' => $cursoPago]);
  }

  /**
   * Descarga de los archivos del curso, guardados fuera de la carpeta publica.
   */
  public function descargar(CursoVideoUri $temario, int $numero)
  {
    $this->autorizar((int) $temario->video_curso_id);

    $archivo = $numero === 1 ? $temario->archivo1_curso : ($numero === 2 ? $temario->archivo2_curso : null);

    abort_unless($archivo && basename($archivo) === $archivo, 404);

    $ruta = 'cursos/' . $archivo;
    abort_unless(Storage::disk('local')->exists($ruta), 404);

    return Storage::disk('local')->download($ruta);
  }
}
