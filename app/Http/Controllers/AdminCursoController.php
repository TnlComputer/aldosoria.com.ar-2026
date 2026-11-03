<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\CursoPago;
use App\Models\CursoVideoUri;
use App\Models\Moneda;
use App\Support\Subidas;
use Illuminate\Http\Request;

class AdminCursoController extends Controller
{
  public const TIPOS = [
    1 => 'Curso con videos',
    2 => 'Curso con videos y archivos',
  ];

  public function index(Request $request)
  {
    $busqueda = trim((string) $request->query('q', ''));

    $cursos = Curso::query()
      ->leftJoin('monedas as mon', 'mon.id', '=', 'cursos.moneda_id')
      ->select('cursos.*', 'mon.simbolo')
      ->when($busqueda !== '', function ($q) use ($busqueda) {
        $like = '%' . addcslashes($busqueda, '%_\\') . '%';
        $q->where(fn ($w) => $w->where('cursos.name', 'like', $like)->orWhere('cursos.title', 'like', $like));
      })
      ->orderBy('cursos.name')
      ->paginate(15)
      ->withQueryString();

    $ids = $cursos->pluck('id');
    $temas = CursoVideoUri::whereIn('video_curso_id', $ids)->selectRaw('video_curso_id, count(*) total')->groupBy('video_curso_id')->pluck('total', 'video_curso_id');
    $alumnos = CursoPago::whereIn('video_curso_id', $ids)->selectRaw('video_curso_id, count(*) total')->groupBy('video_curso_id')->pluck('total', 'video_curso_id');

    return view('admin.cursos.index', compact('cursos', 'busqueda', 'temas', 'alumnos'));
  }

  public function create()
  {
    return view('admin.cursos.form', ['curso' => new Curso(['tipo_curso_id' => 1]), 'monedas' => $this->monedas()]);
  }

  public function store(Request $request)
  {
    $datos = $this->validar($request);

    if ($imagen = $request->file('imagen')) {
      $datos['image_curso'] = Subidas::imagen($imagen, Curso::CARPETA_IMAGENES);
    }

    $curso = Curso::create($datos + ['image_curso' => '']);

    return redirect()->route('cursos.index')->with('success', 'Curso "' . $curso->name . '" creado.');
  }

  public function edit(string $id)
  {
    return view('admin.cursos.form', ['curso' => Curso::findOrFail($id), 'monedas' => $this->monedas()]);
  }

  public function update(Request $request, Curso $curso)
  {
    $datos = $this->validar($request);

    if ($imagen = $request->file('imagen')) {
      $datos['image_curso'] = Subidas::imagen($imagen, Curso::CARPETA_IMAGENES);
    }

    $curso->update($datos);

    return redirect()->route('cursos.index')->with('success', 'Curso "' . $curso->name . '" actualizado.');
  }

  public function destroy(Curso $curso)
  {
    if (CursoPago::where('video_curso_id', $curso->id)->exists()) {
      return back()->with('error', 'No se puede eliminar "' . $curso->name . '": hay alumnos que lo compraron.');
    }

    $curso->delete();

    return redirect()->route('cursos.index')->with('danger', 'Curso eliminado.');
  }

  private function validar(Request $request): array
  {
    $datos = $request->validate([
      'name' => ['required', 'string', 'max:100'],
      'title' => ['required', 'string', 'max:100'],
      'videos_curso_cantidad' => ['required', 'integer', 'min:0', 'max:999'],
      'video_demo_Uri' => ['nullable', 'url:http,https', 'max:255'],
      'moneda_id' => ['required', 'integer', 'exists:monedas,id'],
      'coast' => ['required', 'numeric', 'min:-1', 'max:999999'],
      'tipo_curso_id' => ['required', 'in:1,2'],
      'imagen' => Subidas::REGLAS_IMAGEN,
    ], [], [
      'name' => 'título',
      'title' => 'subtítulo',
      'videos_curso_cantidad' => 'cantidad de videos',
      'video_demo_Uri' => 'video de muestra',
      'moneda_id' => 'moneda',
      'tipo_curso_id' => 'tipo de curso',
    ]);

    unset($datos['imagen']);
    $datos['video_demo_Uri'] = $datos['video_demo_Uri'] ?? '';

    return $datos;
  }

  private function monedas(): array
  {
    return Moneda::orderBy('moneda')->get()->mapWithKeys(fn ($m) => [$m->id => $m->moneda . ' (' . $m->simbolo . ')'])->all();
  }
}
