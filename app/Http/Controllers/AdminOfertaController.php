<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use Illuminate\Http\Request;

class AdminOfertaController extends Controller
{
  public function index()
  {
    return view('admin.oferta.index', ['oferta' => Oferta::firstOrFail()]);
  }

  public function update(Request $request, Oferta $oferta)
  {
    $request->validate([
      'imagen' => ['required', 'file', 'image', 'mimes:jpg,jpeg', 'max:10240'],
    ], [], ['imagen' => 'imagen de la oferta']);

    // La oferta siempre se publica como img/Ofertas/oferta.jpg
    $request->file('imagen')->move(public_path('img/Ofertas'), 'oferta.jpg');
    $oferta->update(['oferta' => 'oferta.jpg']);

    return redirect()->route('adminOferta.index')->with('success', 'Oferta actualizada. Ya se muestra en el sitio.');
  }
}
