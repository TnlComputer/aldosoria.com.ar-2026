<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\CursoPago;
use App\Models\CursoUsuario;
use App\Models\CursoVideoUri;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    if (Auth::user()->isAdmin()) {
      return $this->panelAdmin();
    }

    $cursosPagos = DB::table('cursos_pagos_videos as curp')
      ->join('cursos as cur', 'cur.id', '=', 'curp.video_curso_id')
      ->where('curp.user_curso_id', '=', Auth::id())
      ->select('curp.id', 'curp.video_curso_id', 'curp.user_curso_id', 'curp.vto_curso', 'cur.name', 'cur.title', 'cur.image_curso', 'cur.video_demo_Uri')
      ->orderByDesc('curp.vto_curso')
      ->orderBy('cur.id')
      ->paginate(6);

    // Solo el temario de los cursos que se muestran en esta pagina
    $temarios = CursoVideoUri::whereIn('video_curso_id', $cursosPagos->pluck('video_curso_id'))
      ->orderBy('nro_video_id')
      ->get(['video_curso_id', 'descripcion', 'tipo_curso']);

    return view('pages.dashboard', ['cursosPagos' => $cursosPagos, 'temarios' => $temarios]);
  }

  /**
   * Resumen del sitio para los administradores.
   */
  private function panelAdmin()
  {
    $hoy = Carbon::today()->toDateString();
    $hace30 = Carbon::today()->subDays(30)->toDateString();

    $datos = [
      'alumnos' => User::where('roles', 'ROLE_USER')->count(),
      'nuevos' => User::where('created_at', '>=', $hace30)->count(),
      'cursos' => Curso::count(),
      'ventas30' => CursoUsuario::where('fecha_inscripcion', '>=', $hace30)->count(),
      'vigentes' => CursoPago::where('vto_curso', '>=', $hoy)->count(),
      'porVencer' => CursoPago::whereBetween('vto_curso', [$hoy, Carbon::today()->addDays(7)->toDateString()])->count(),
    ];

    $ultimas = DB::table('cursos_usuarios as cu')
      ->join('users as u', 'u.id', '=', 'cu.user_curso_id')
      ->select('cu.id', 'cu.user_curso_id', 'cu.nombre_curso', 'cu.titulo_curso', 'cu.forma_pago_curso', 'cu.fecha_inscripcion', 'u.name', 'u.last_name')
      ->orderByDesc('cu.fecha_inscripcion')
      ->orderByDesc('cu.id')
      ->limit(8)
      ->get();

    return view('admin.inicio', compact('datos', 'ultimas'));
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    return redirect()->route('video.show', $id);
  }
}
