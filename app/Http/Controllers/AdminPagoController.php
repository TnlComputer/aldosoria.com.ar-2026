<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\CursoPago;
use App\Models\CursoUsuario;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPagoController extends Controller
{
  public const FORMAS_PAGO = [
    0 => 'Gratis',
    1 => 'Efectivo',
    2 => 'Transferencia',
    3 => 'PayPal',
    4 => 'MercadoPago',
  ];

  /** Formas de pago que se pueden cargar a mano (PayPal y MercadoPago se acreditan solos) */
  private const FORMAS_MANUALES = [0, 1, 2];

  /**
   * Compras con el alumno y el vencimiento de su acceso.
   */
  private function compras(): Builder
  {
    return DB::table('cursos_usuarios as cu')
      ->join('users as u', 'u.id', '=', 'cu.user_curso_id')
      ->leftJoin('cursos_pagos_videos as cvp', function ($j) {
        $j->on('cvp.user_curso_id', '=', 'cu.user_curso_id')->on('cvp.video_curso_id', '=', 'cu.curso_id');
      })
      ->select(
        'cu.id', 'cu.user_curso_id', 'cu.curso_id', 'cu.nombre_curso', 'cu.titulo_curso', 'cu.forma_pago_curso',
        'cu.fecha_inscripcion', 'cu.pago_curso', 'cu.payment_id', 'cu.orderId', 'cu.amount', 'cu.currency',
        'u.name as user_name', 'u.last_name as user_last_name', 'u.email as user_email',
        'cvp.vto_curso'
      );
  }

  public function index(Request $request)
  {
    $filtros = $request->validate([
      'q' => ['nullable', 'string', 'max:100'],
      'forma' => ['nullable', 'in:' . implode(',', array_keys(self::FORMAS_PAGO))],
      'acceso' => ['nullable', 'in:vigente,vencido'],
    ]);

    $busqueda = trim($filtros['q'] ?? '');
    $forma = $filtros['forma'] ?? null;
    $acceso = $filtros['acceso'] ?? null;
    $hoy = Carbon::today()->toDateString();

    $pagos = $this->compras()
      ->when($busqueda !== '', function ($q) use ($busqueda) {
        foreach (preg_split('/\s+/', $busqueda) as $palabra) {
          $like = '%' . addcslashes($palabra, '%_\\') . '%';
          $q->where(fn ($w) => $w->where('u.name', 'like', $like)->orWhere('u.last_name', 'like', $like)
            ->orWhere('u.email', 'like', $like)->orWhere('cu.nombre_curso', 'like', $like)->orWhere('cu.titulo_curso', 'like', $like));
        }
      })
      ->when($forma !== null, fn ($q) => $q->where('cu.forma_pago_curso', $forma))
      ->when($acceso === 'vigente', fn ($q) => $q->where('cvp.vto_curso', '>=', $hoy))
      ->when($acceso === 'vencido', fn ($q) => $q->where(fn ($w) => $w->where('cvp.vto_curso', '<', $hoy)->orWhereNull('cvp.vto_curso')))
      ->orderByDesc('cu.fecha_inscripcion')
      ->orderByDesc('cu.id')
      ->paginate(20)
      ->withQueryString();

    return view('admin.pagos.index', compact('pagos', 'busqueda', 'forma', 'acceso'));
  }

  public function create(Request $request)
  {
    return view('admin.pagos.form', [
      'usuarios' => $this->usuarios(),
      'cursos' => Curso::orderBy('name')->get()->mapWithKeys(fn ($c) => [$c->id => $c->name . ' - ' . $c->title])->all(),
      'formas' => array_intersect_key(self::FORMAS_PAGO, array_flip(self::FORMAS_MANUALES)),
      'usuarioElegido' => $request->query('usuario'),
    ]);
  }

  public function store(Request $request)
  {
    $request->validate([
      'user_curso_id' => ['required', 'integer', 'exists:users,id'],
      'video_curso_id' => ['required', 'integer', 'exists:cursos,id'],
      'vto_curso' => ['required', 'integer', 'min:1', 'max:3650'],
      'forma_pago_curso' => ['required', 'in:' . implode(',', self::FORMAS_MANUALES)],
      'amount' => ['nullable', 'numeric', 'min:0', 'max:9999999'],
    ], [], [
      'user_curso_id' => 'alumno',
      'video_curso_id' => 'curso',
      'forma_pago_curso' => 'forma de pago',
      'amount' => 'importe',
    ]);

    $curso = Curso::findOrFail($request->video_curso_id);

    DB::transaction(function () use ($request, $curso) {
      CursoPago::updateOrCreate(
        ['user_curso_id' => $request->user_curso_id, 'video_curso_id' => $curso->id],
        ['vto_curso' => Carbon::now()->addDays((int) $request->vto_curso)]
      );

      CursoUsuario::create([
        'user_curso_id' => $request->user_curso_id,
        'curso_id' => $curso->id,
        'nombre_curso' => $curso->name,
        'titulo_curso' => $curso->title,
        'forma_pago_curso' => (int) $request->forma_pago_curso,
        'fecha_inscripcion' => Carbon::now()->toDateString(),
        'pago_curso' => 'P',
        'amount' => (float) ($request->amount ?? 0),
        'currency' => $request->amount ? 'AR$' : null,
      ]);
    });

    return redirect()->route('pagos.show', $request->user_curso_id)->with('success', 'Curso "' . $curso->name . '" asignado.');
  }

  /**
   * Compras de un alumno.
   */
  public function show(string $id)
  {
    $usuario = User::findOrFail($id);

    $pagos = $this->compras()
      ->where('cu.user_curso_id', $usuario->id)
      ->orderByDesc('cu.fecha_inscripcion')
      ->paginate(20);

    return view('admin.pagos.usuario', compact('usuario', 'pagos'));
  }

  public function edit(string $id)
  {
    $pago = $this->compras()->where('cu.id', $id)->first();
    abort_unless($pago, 404);

    return view('admin.pagos.editar', ['pago' => $pago]);
  }

  public function update(Request $request, string $id)
  {
    $request->validate([
      'pago_curso' => ['required', 'in:P,I'],
      'vto_curso' => ['nullable', 'integer', 'min:1', 'max:3650'],
    ], [], ['vto_curso' => 'días de acceso', 'pago_curso' => 'estado']);

    $cursoUsuario = CursoUsuario::findOrFail($id);
    $cursoUsuario->update(['pago_curso' => $request->pago_curso]);

    if ((int) $request->vto_curso > 0) {
      // Se usan los datos del registro, no los enviados por el formulario
      CursoPago::updateOrCreate(
        ['user_curso_id' => $cursoUsuario->user_curso_id, 'video_curso_id' => $cursoUsuario->curso_id],
        ['vto_curso' => Carbon::now()->addDays((int) $request->vto_curso)]
      );
    }

    return redirect()->route('pagos.show', $cursoUsuario->user_curso_id)->with('success', 'Compra actualizada.');
  }

  /**
   * Quita el acceso a un curso (no borra el registro de la compra).
   */
  public function destroy(CursoPago $pago)
  {
    $usuario = $pago->user_curso_id;
    $pago->delete();

    return redirect()->route('pagos.show', $usuario)->with('danger', 'Acceso al curso quitado.');
  }

  private function usuarios(): array
  {
    return User::orderBy('last_name')->orderBy('name')->get(['id', 'name', 'last_name', 'email'])
      ->mapWithKeys(fn ($u) => [$u->id => trim($u->last_name . ', ' . $u->name, ', ') . ' — ' . $u->email])
      ->all();
  }
}
