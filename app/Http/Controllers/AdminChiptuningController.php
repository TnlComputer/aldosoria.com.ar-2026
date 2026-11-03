<?php

namespace App\Http\Controllers;

use App\Models\Marcas_autos;
use App\Models\Marcas_images_autos;
use App\Support\FichaChip;
use App\Support\Subidas;
use Illuminate\Http\Request;

class AdminChiptuningController extends Controller
{
  private const CARPETA_GRAFICOS = 'black/img/Graficos_Banco_Pruebas';

  private const CAMPOS = [
    'marca', 'modelo', 'motor', 'modelo_motor', 'anio', 'combustible', 'transmicion', 'ecu',
    'potencia_original', 'torque_original',
    'stage1', 'stage2', 'stage3',
    'torque_stg1', 'torque_stg2', 'torque_stg3',
    'configuracion_stg1', 'configuracion_stg2', 'configuracion_stg3',
  ];

  /**
   * Valida el formulario y devuelve los datos listos para guardar.
   */
  private function datos(Request $request): array
  {
    // Los largos coinciden con las columnas de la tabla marcas_autos
    $request->validate([
      'marca' => ['required', 'string', 'max:255', 'exists:marcas_images_autos,marca'],
      'modelo' => ['required', 'string', 'max:255'],
      'motor' => ['nullable', 'string', 'max:255'],
      'modelo_motor' => ['nullable', 'string', 'max:255'],
      'anio' => ['nullable', 'string', 'max:30'],
      'ecu' => ['nullable', 'string', 'max:255'],
      'combustible' => ['nullable', 'string', 'max:30'],
      'transmicion' => ['nullable', 'string', 'max:50'],
      'potencia_original' => ['nullable', 'string', 'max:50'],
      'torque_original' => ['nullable', 'string', 'max:50'],
      'stage1' => ['nullable', 'string', 'max:255'],
      'stage2' => ['nullable', 'string', 'max:255'],
      'stage3' => ['nullable', 'string', 'max:255'],
      'torque_stg1' => ['nullable', 'string', 'max:100'],
      'torque_stg2' => ['nullable', 'string', 'max:100'],
      'torque_stg3' => ['nullable', 'string', 'max:100'],
      'configuracion_stg1' => ['nullable', 'string', 'max:255'],
      'configuracion_stg2' => ['nullable', 'string', 'max:255'],
      'configuracion_stg3' => ['nullable', 'string', 'max:255'],
      'img_st1' => Subidas::REGLAS_IMAGEN,
      'img_st2' => Subidas::REGLAS_IMAGEN,
      'img_st3' => Subidas::REGLAS_IMAGEN,
    ], [], [
      'anio' => 'año',
      'transmicion' => 'transmisión',
      'modelo_motor' => 'código de motor',
    ]);

    // Mismo formato que el resto de las fichas: "180 HP", "250 Nm", "Diesel"...
    $chip = FichaChip::normalizar(array_map(fn ($v) => (string) $v, $request->only(self::CAMPOS)));
    $prefijo = $request->marca . '-' . $request->modelo . '-' . $request->motor;

    foreach (['img_st1', 'img_st2', 'img_st3'] as $campo) {
      if ($imagen = $request->file($campo)) {
        $chip[$campo] = Subidas::imagen($imagen, self::CARPETA_GRAFICOS, $prefijo);
      }
    }

    return $chip;
  }

  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $busqueda = trim((string) $request->query('q', ''));
    $marca = trim((string) $request->query('marca', ''));

    $chips = Marcas_autos::query()
      ->when($marca !== '', fn ($q) => $q->where('marca', $marca))
      ->when($busqueda !== '', function ($q) use ($busqueda) {
        foreach (preg_split('/\s+/', $busqueda) as $palabra) {
          $like = '%' . addcslashes($palabra, '%_\\') . '%';
          $q->where(fn ($w) => $w->where('marca', 'like', $like)->orWhere('modelo', 'like', $like)
            ->orWhere('motor', 'like', $like)->orWhere('ecu', 'like', $like)->orWhere('anio', 'like', $like));
        }
      })
      ->orderBy('marca')
      ->orderBy('modelo')
      ->paginate(25)
      ->withQueryString();

    return view('admin.chiptuning.index', [
      'chips' => $chips,
      'busqueda' => $busqueda,
      'marca' => $marca,
      'marcas' => $this->marcas(),
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('admin.chiptuning.form', ['chip' => new Marcas_autos(), 'marcas' => $this->marcas()]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $chip = Marcas_autos::create($this->datos($request));
    return redirect()->route('chiptuning.index')->with('success', 'Ficha de ' . $chip->marca . ' ' . $chip->modelo . ' creada.');
  }

  private function marcas(): array
  {
    return Marcas_images_autos::orderBy('marca')->pluck('marca', 'marca')->all();
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    return redirect()->route('chiptuning.edit', $id);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    return view('admin.chiptuning.form', ['chip' => Marcas_autos::findOrFail($id), 'marcas' => $this->marcas()]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Marcas_autos $chiptuning)
  {
    $chiptuning->update($this->datos($request));
    return redirect()->route('chiptuning.index')->with('success', 'Ficha actualizada.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Marcas_autos $chiptuning)
  {
    $chiptuning->delete();
    return redirect()->route('chiptuning.index')->with('danger', 'Ficha eliminada.');
  }
}
