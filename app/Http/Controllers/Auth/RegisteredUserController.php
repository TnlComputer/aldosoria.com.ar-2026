<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pais;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Support\AntiBot;
use App\Support\Reglas;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
  /**
   * Display the registration view.
   */
  public function create(): View
  {
    $paises = Pais::all();

    return view('auth.register', ["paises" => $paises]);
  }

  /**
   * Handle an incoming registration request.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(Request $request): RedirectResponse
  {
    AntiBot::verificar($request);

    $request->merge([
      'name' => Reglas::limpiar($request->name),
      'last_name' => Reglas::limpiar($request->last_name),
      'phone' => Reglas::limpiar($request->phone),
      'email' => Reglas::normalizarEmail($request->email),
    ]);

    $request->validate([
      'name' => Reglas::nombre(),
      'last_name' => Reglas::nombre(),
      'phone' => [...Reglas::telefono(), 'unique:' . User::class],
      'email' => [...Reglas::email(), 'unique:' . User::class],
      'password' => ['required', 'confirmed', Rules\Password::defaults()],
      'pais_id' => ['required', 'integer', 'exists:pais,id'],
      'whatsapp' => Reglas::siNo(),
      'tlgram' => Reglas::siNo(),
    ], [
      'name.regex' => 'El nombre solo puede tener letras.',
      'last_name.regex' => 'El apellido solo puede tener letras.',
      'phone.regex' => 'El teléfono solo puede tener números, espacios, guiones y el signo +.',
    ]);

    if (Reglas::nombreDeBot($request->name, $request->last_name)) {
      Log::warning('Registro rechazado por patron de bot', ['ip' => $request->ip(), 'email' => $request->email]);

      throw ValidationException::withMessages(['last_name' => 'Revisá el apellido ingresado.']);
    }

    $user = new User([
      'name' => $request->name,
      'last_name' => $request->last_name,
      'phone' => $request->phone,
      'email' => $request->email,
      'pais_id' => $request->pais_id,
      'whatsapp' => $request->whatsapp,
      'tlgram' => $request->tlgram,
      'password' => Hash::make($request->password),
    ]);
    $user->roles = 'ROLE_USER';
    $user->save();

    event(new Registered($user));

    Auth::login($user);

    return redirect(RouteServiceProvider::HOME);
  }
}
