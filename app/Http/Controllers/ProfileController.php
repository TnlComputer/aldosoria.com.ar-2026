<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileActRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Pais;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
  /**
   * Display the user's profile form.
   */
  public function edit(Request $request): View
  {
    $user = $request->user();
    $paises = Pais::all();
    $paisUser = Pais::find($user->pais_id);

    // dd($user, $usuario, $paises, $paisUser);
    // return view('profile.edit', [
    //   'user' => $request->user(),
    // ]);
    return view('profile.edit', ['user' => $user, 'paises' => $paises, 'paisUser' => $paisUser]);
  }

  /**
   * Update the user's profile information.
   */
  public function update(ProfileUpdateRequest $request): RedirectResponse
  {
    $request->user()->fill($request->validated());

    if ($request->user()->isDirty('email')) {
      $request->user()->email_verified_at = null;
    }

    $request->user()->save();

    return Redirect::route('profile.edit')->with('status', 'profile-updated');
  }

  public function act(ProfileActRequest $request)
  {

    // dd($request);
    $request->user()->fill($request->validated());

    if ($request->user()->isDirty('email')) {
      $request->user()->email_verified_at = null;
    }

    $request->user()->save();

    // // return Redirect::route('dashboard');
    return Redirect::route('profile.edit')->with('status', 'profile-updated');
  }

  /**
   * Delete the user's account.
   */
  public function destroy(Request $request): RedirectResponse
  {
    $request->validateWithBag('userDeletion', [
      'password' => ['required', 'current_password'],
    ]);

    $user = $request->user();

    Auth::logout();

    $user->delete();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return Redirect::to('/');
  }
}
