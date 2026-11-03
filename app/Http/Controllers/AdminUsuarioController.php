<?php

namespace App\Http\Controllers;

use App\Http\Requests\userUpdateRequest;
use App\Models\Pais;
use App\Models\User;
use App\Support\Reglas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminUsuarioController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $filtros = $request->validate([
      'q' => ['nullable', 'string', 'max:100'],
      'rol' => ['nullable', 'in:todos,ROLE_USER,ROLE_ADMIN'],
      'cursos' => ['nullable', 'in:todos,con,sin'],
    ]);

    $busqueda = trim($filtros['q'] ?? '');
    $rol = $filtros['rol'] ?? 'ROLE_USER';
    $cursos = $filtros['cursos'] ?? 'todos';

    $usuarios = User::with('pais:id,nombre')
      ->withCount('cursosPagos')
      ->when($rol !== 'todos', fn ($q) => $q->where('roles', $rol))
      ->when($cursos === 'con', fn ($q) => $q->has('cursosPagos'))
      ->when($cursos === 'sin', fn ($q) => $q->doesntHave('cursosPagos'))
      ->when($busqueda !== '', function ($q) use ($busqueda) {
        // Cada palabra debe aparecer en alguno de los campos: "juan perez" encuentra nombre + apellido
        foreach (preg_split('/\s+/', $busqueda) as $palabra) {
          $like = '%' . addcslashes($palabra, '%_\\') . '%';
          $q->where(function ($w) use ($like) {
            $w->where('name', 'like', $like)
              ->orWhere('last_name', 'like', $like)
              ->orWhere('email', 'like', $like)
              ->orWhere('phone', 'like', $like);
          });
        }
      })
      ->orderBy('last_name')
      ->orderBy('name')
      ->paginate(25)
      ->withQueryString();

    return view('admin.usuarios.index', [
      'usuarios' => $usuarios,
      'busqueda' => $busqueda,
      'rol' => $rol,
      'cursos' => $cursos,
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $paises = Pais::orderBy('nombre')->pluck('nombre', 'id')->all();
    return view('admin.usuarios.form', ['usuario' => new User(['whatsapp' => 'SI', 'tlgram' => 'NO']), 'paises' => $paises]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->merge(['email' => Reglas::normalizarEmail($request->email)]);

    $datos = $request->validate([
      'name' => Reglas::nombre(),
      'last_name' => Reglas::nombre(),
      'phone' => Reglas::telefono(),
      'email' => [...Reglas::email(), 'unique:users,email'],
      'pais_id' => ['nullable', 'integer', 'exists:pais,id'],
      'whatsapp' => Reglas::siNo(),
      'tlgram' => Reglas::siNo(),
      'roles' => ['required', 'in:ROLE_USER,ROLE_ADMIN'],
      'password' => ['required', 'confirmed', Password::defaults()],
    ]);

    $usuario = new User();
    $usuario->fill([
      'name' => $datos['name'],
      'last_name' => $datos['last_name'],
      'phone' => $datos['phone'],
      'email' => $datos['email'],
      'pais_id' => $datos['pais_id'] ?? null,
      'whatsapp' => $datos['whatsapp'] ?? 'NO',
      'tlgram' => $datos['tlgram'] ?? 'NO',
      'password' => Hash::make($datos['password']),
    ]);
    $usuario->roles = $datos['roles'];
    $usuario->email_verified_at = now();
    $usuario->save();

    return redirect()->route('usuarios.index')->with('success', 'Usuario creado.');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    $usuario = User::findOrFail($id);
    $paises = Pais::orderBy('nombre')->pluck('nombre', 'id')->all();

    return view('admin.usuarios.form', ['usuario' => $usuario, 'paises' => $paises]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(userUpdateRequest $request, User $usuario)
  {
    $request->validate(['roles' => ['required', 'in:ROLE_USER,ROLE_ADMIN']]);

    $usuario->fill($request->safe()->except(['roles']));
    $usuario->roles = $request->roles;
    $usuario->save();

    return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(User $usuario)
  {
    if ($usuario->id === auth()->id()) {
      return back()->with('error', 'No podés eliminar tu propio usuario.');
    }

    // Quien compro o tiene cursos asignados nunca se borra
    if ($usuario->tieneCursos()) {
      return back()->with('error', 'No se puede eliminar a ' . $usuario->name . ' ' . $usuario->last_name . ': tiene cursos comprados.');
    }

    $usuario->delete();
    return redirect()->route('usuarios.index')->with('danger', 'Usuario eliminado.');
  }
}
