<?php

namespace App\Http\Controllers;

use App\Models\Marcas_autos;
use App\Models\Marcas_images_autos;
use App\Support\Subidas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminMarcasController extends Controller
{
  public const CARPETA = 'black/img/Marcas';

  public function index(Request $request)
  {
    $busqueda = trim((string) $request->query('q', ''));

    $marcas = Marcas_images_autos::query()
      ->when($busqueda !== '', fn ($q) => $q->where('marca', 'like', '%' . addcslashes($busqueda, '%_\\') . '%'))
      ->orderBy('marca')
      ->paginate(24)
      ->withQueryString();

    $fichas = Marcas_autos::whereIn('marca', $marcas->pluck('marca'))
      ->selectRaw('marca, count(*) total')->groupBy('marca')->pluck('total', 'marca');

    return view('admin.marcas.index', compact('marcas', 'busqueda', 'fichas'));
  }

  public function create()
  {
    return view('admin.marcas.form', ['marca' => new Marcas_images_autos()]);
  }

  public function store(Request $request)
  {
    $request->validate([
      'marca' => ['required', 'string', 'max:100', 'unique:marcas_images_autos,marca'],
      'imagen' => ['required', ...array_slice(Subidas::REGLAS_IMAGEN, 1)],
    ], [], ['imagen' => 'logo']);

    $marca = Marcas_images_autos::create([
      'marca' => $request->marca,
      'foto' => Subidas::imagen($request->file('imagen'), self::CARPETA),
    ]);

    return redirect()->route('marcas.index')->with('success', 'Marca "' . $marca->marca . '" creada.');
  }

  public function show(string $id)
  {
    return redirect()->route('marcas.edit', $id);
  }

  public function edit(string $id)
  {
    return view('admin.marcas.form', ['marca' => Marcas_images_autos::findOrFail($id)]);
  }

  public function update(Request $request, Marcas_images_autos $marca)
  {
    $request->validate([
      'marca' => ['required', 'string', 'max:100', Rule::unique('marcas_images_autos', 'marca')->ignore($marca->id)],
      'imagen' => Subidas::REGLAS_IMAGEN,
    ], [], ['imagen' => 'logo']);

    $datos = ['marca' => $request->marca];

    if ($imagen = $request->file('imagen')) {
      $datos['foto'] = Subidas::imagen($imagen, self::CARPETA);
    }

    // Si cambia el nombre, las fichas de chiptuning siguen apuntando a la marca
    if ($marca->marca !== $request->marca) {
      Marcas_autos::where('marca', $marca->marca)->update(['marca' => $request->marca]);
    }

    $marca->update($datos);

    return redirect()->route('marcas.index')->with('success', 'Marca actualizada.');
  }

  public function destroy(Marcas_images_autos $marca)
  {
    if (Marcas_autos::where('marca', $marca->marca)->exists()) {
      return back()->with('error', 'No se puede eliminar "' . $marca->marca . '": tiene fichas de chiptuning cargadas.');
    }

    $marca->delete();

    return redirect()->route('marcas.index')->with('danger', 'Marca eliminada.');
  }
}
