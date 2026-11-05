<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\CursoPago;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CursoController extends Controller
{
  public function index()
  {
    $cursos = Curso::leftJoin('monedas as mon', 'mon.id', '=', 'cursos.moneda_id')
      ->select('cursos.*', 'mon.simbolo')
      ->selectSub(
        DB::table('cursos_videos_uris')->selectRaw('COUNT(*)')->whereColumn('video_curso_id', 'cursos.id'),
        'clases'
      )
      ->orderByDesc('cursos.id')
      ->paginate(9);

    // Cursos que el alumno ya tiene vigentes, para marcarlos en el listado
    $comprados = Auth::check()
      ? CursoPago::where('user_curso_id', Auth::id())
        ->whereDate('vto_curso', '>=', now()->toDateString())
        ->pluck('video_curso_id')->all()
      : [];

    return view('pages.cursos', ['cursos' => $cursos, 'comprados' => $comprados]);
  }
}
