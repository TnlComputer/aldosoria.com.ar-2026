<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Moneda;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminMonedaController extends Controller
{
  public function index()
  {
    $monedas = Moneda::orderBy('moneda')->get();
    $cursos = Curso::selectRaw('moneda_id, count(*) total')->groupBy('moneda_id')->pluck('total', 'moneda_id');

    return view('admin.monedas.index', compact('monedas', 'cursos'));
  }

  public function create()
  {
    return view('admin.monedas.form', ['moneda' => new Moneda()]);
  }

  public function store(Request $request)
  {
    $moneda = Moneda::create($this->validar($request));

    return redirect()->route('monedas.index')->with('success', 'Moneda ' . $moneda->moneda . ' creada.');
  }

  public function edit(string $id)
  {
    return view('admin.monedas.form', ['moneda' => Moneda::findOrFail($id)]);
  }

  public function update(Request $request, Moneda $moneda)
  {
    $moneda->update($this->validar($request, $moneda));

    return redirect()->route('monedas.index')->with('success', 'Moneda ' . $moneda->moneda . ' actualizada.');
  }

  public function destroy(Moneda $moneda)
  {
    if (Curso::where('moneda_id', $moneda->id)->exists()) {
      return back()->with('error', 'No se puede eliminar ' . $moneda->moneda . ': hay cursos con esa moneda.');
    }

    $moneda->delete();

    return redirect()->route('monedas.index')->with('danger', 'Moneda eliminada.');
  }

  private function validar(Request $request, ?Moneda $moneda = null): array
  {
    $request->merge(['moneda' => strtoupper(trim((string) $request->moneda))]);

    return $request->validate([
      'moneda' => ['required', 'alpha', 'size:3', Rule::unique('monedas', 'moneda')->ignore($moneda?->id)],
      'simbolo' => ['required', 'string', 'max:10'],
      'name' => ['required', 'string', 'max:50'],
      'pais' => ['required', 'string', 'max:50'],
      'cambio' => ['required', 'numeric', 'min:0', 'max:99999999'],
    ], [], [
      'moneda' => 'código',
      'simbolo' => 'símbolo',
      'name' => 'nombre',
      'cambio' => 'cotización',
    ]);
  }
}
