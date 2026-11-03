<?php

namespace App\Http\Controllers;

use App\Models\Novedad;
use Illuminate\Http\Request;

class AdminNovedadesController extends Controller
{
  public const ORIGENES = [4 => 'YouTube', 1 => 'Facebook'];

  public const TIPOS = [1 => 'Video', 2 => 'Foto'];

  public function index(Request $request)
  {
    $busqueda = trim((string) $request->query('q', ''));

    $novedades = Novedad::query()
      ->when($busqueda !== '', fn ($q) => $q->where('novedades', 'like', '%' . addcslashes($busqueda, '%_\\') . '%'))
      ->orderByDesc('fecha')
      ->orderByDesc('id')
      ->paginate(15)
      ->withQueryString();

    return view('admin.novedades.index', compact('novedades', 'busqueda'));
  }

  public function create()
  {
    return view('admin.novedades.form', ['novedad' => new Novedad(['fecha' => now()->toDateString(), 'tipo_novedad' => 4, 'foto_novedad' => 1])]);
  }

  public function store(Request $request)
  {
    Novedad::create($this->validar($request));

    return redirect()->route('novedad.index')->with('success', 'Novedad publicada.');
  }

  public function show(string $id)
  {
    return redirect()->route('novedad.edit', $id);
  }

  public function edit(string $id)
  {
    return view('admin.novedades.form', ['novedad' => Novedad::findOrFail($id)]);
  }

  public function update(Request $request, Novedad $novedad)
  {
    $novedad->update($this->validar($request));

    return redirect()->route('novedad.index')->with('success', 'Novedad actualizada.');
  }

  public function destroy(Novedad $novedad)
  {
    $novedad->delete();

    return redirect()->route('novedad.index')->with('danger', 'Novedad eliminada.');
  }

  private function validar(Request $request): array
  {
    return $request->validate([
      'novedades' => ['required', 'string', 'max:255'],
      'fecha' => ['required', 'date'],
      'uri_novedad' => ['required', 'url:http,https', 'max:255'],
      'tipo_novedad' => ['required', 'in:' . implode(',', array_keys(self::ORIGENES))],
      'foto_novedad' => ['required', 'in:' . implode(',', array_keys(self::TIPOS))],
    ], [], [
      'novedades' => 'título',
      'uri_novedad' => 'enlace',
      'tipo_novedad' => 'origen',
      'foto_novedad' => 'tipo',
    ]);
  }
}
