<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\CursoVideoUri;
use App\Support\Subidas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminTemarioController extends Controller
{
  private const CAMPOS = ['nro_video_id', 'descripcion', 'uri_video', 'user_video', 'clave_video', 'tipo_curso'];

  private function validar(Request $request): void
  {
    $request->validate([
      'nro_video_id' => ['nullable', 'integer', 'min:0'],
      'descripcion' => ['required', 'string', 'max:255'],
      'uri_video' => ['nullable', 'url:http,https', 'max:255'],
      'tipo_curso' => ['required', 'in:1,2'],
      'img_video' => Subidas::REGLAS_IMAGEN,
      'archivo1_curso' => Subidas::REGLAS_ARCHIVO_CURSO,
      'archivo2_curso' => Subidas::REGLAS_ARCHIVO_CURSO,
    ]);
  }

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    // El temario se administra desde cada curso
    return redirect()->route('cursos.index');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(Request $request)
  {
    $curso = Curso::find($request->query('curso'));
    $siguiente = $curso ? (int) CursoVideoUri::where('video_curso_id', $curso->id)->max('nro_video_id') + 1 : 1;

    return view('admin.temario.form', [
      'temario' => new CursoVideoUri(['nro_video_id' => $siguiente, 'tipo_curso' => 1, 'video_curso_id' => $curso?->id]),
      'curso' => $curso,
      'cursos' => Curso::orderBy('name')->get()->mapWithKeys(fn ($c) => [$c->id => $c->name . ' - ' . $c->title])->all(),
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate(['id_curso' => ['required', 'integer', 'exists:cursos,id']]);
    $this->validar($request);

    $id = $request->id_curso;
    $curso = Curso::findOrFail($id);

    $data = $request->only(self::CAMPOS);
    $data['nombre_curso'] = $curso->name;
    $data['nombre_video'] = $curso->title;
    $data['video_curso_id'] = $id;

    if ($imagen = $request->file('img_video')) {
      $data['img_video'] = Subidas::imagen($imagen, 'img/cursos');
    }

    if ($archivo1 = $request->file('archivo1_curso')) {
      $data['archivo1_curso'] = Subidas::archivoCurso($archivo1, '1');
    }

    if ($archivo2 = $request->file('archivo2_curso')) {
      $data['archivo2_curso'] = Subidas::archivoCurso($archivo2, '2');
    }

    CursoVideoUri::create($data);

    return redirect()->route('temario.show', $id)
      ->with('success', 'Tema agregado al curso.');
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    $curso = Curso::findOrFail($id);
    $temarios = CursoVideoUri::where('video_curso_id', $id)->orderBy('nro_video_id', 'ASC')->get();

    return view('admin.temario.index', ['temarios' => $temarios, 'curso' => $curso]);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(CursoVideoUri $temario)
  {
    return view('admin.temario.form', [
      'temario' => $temario,
      'curso' => Curso::find($temario->video_curso_id),
      'cursos' => [],
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, CursoVideoUri $temario)
  {
    $this->validar($request);

    $updTemario = $request->only(self::CAMPOS);

    // Video (tipo 1)
    if ($request->tipo_curso == 1) {
      if ($imagen = $request->file('img_video')) {
        $updTemario['img_video'] = Subidas::imagen($imagen, 'img/cursos');
      }

      $updTemario['archivo1_curso'] = null;
      $updTemario['archivo2_curso'] = null;
    }

    // Archivo (tipo 2)
    if ($request->tipo_curso == 2) {
      $updTemario['uri_video'] = null;

      if ($archivo1 = $request->file('archivo1_curso')) {
        $updTemario['archivo1_curso'] = Subidas::archivoCurso($archivo1, '1');
      }

      if ($archivo2 = $request->file('archivo2_curso')) {
        $updTemario['archivo2_curso'] = Subidas::archivoCurso($archivo2, '2');
      }
    }

    $temario->update($updTemario);

    return redirect()
      ->route('temario.show', $temario->video_curso_id)
      ->with('success', 'Tema actualizado.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(CursoVideoUri $temario)
  {
    $temario->delete();
    return redirect()->route('temario.show', $temario->video_curso_id)->with('danger', 'Tema eliminado.');
  }
}
