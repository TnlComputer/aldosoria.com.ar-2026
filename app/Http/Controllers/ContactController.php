<?php

namespace App\Http\Controllers;

use App\Support\AntiBot;
use App\Support\Reglas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('pages.contacto');
  }

  public function enviar(Request $request)
  {
    AntiBot::verificar($request);

    $sinHtml = 'not_regex:/<[^>]*>|\[url|\[link/i';

    $datos = $request->validate([
      'txtNombre' => Reglas::nombre(),
      'txtTelefono' => Reglas::telefono(),
      'txtEmail' => ['required', 'email:rfc,strict', 'max:150'],
      'txtAsunto' => ['required', 'string', 'max:150', $sinHtml, 'not_regex:/https?:|www\./i'],
      'txtMensaje' => [
        'required', 'string', 'min:5', 'max:5000', $sinHtml,
        // El spam suele venir cargado de enlaces
        function ($atributo, $valor, $fallar) {
          if (preg_match_all('/https?:\/\/|www\./i', $valor) > 2) {
            $fallar('El mensaje no puede tener más de 2 enlaces.');
          }
        },
      ],
    ], [
      'txtNombre.regex' => 'El nombre solo puede tener letras.',
      'not_regex' => 'El campo :attribute no puede tener código ni enlaces.',
    ]);

    // Evita inyeccion de cabeceras en el asunto
    $asunto = str_replace(["\r", "\n"], ' ', $datos['txtAsunto']);

    try {
      Mail::send('emails.contacto', ['datos' => $datos], function ($message) use ($datos, $asunto) {
        $message->to(config('mail.admin_address'), 'Aldo Soria')
          ->replyTo($datos['txtEmail'], str_replace(["\r", "\n"], ' ', $datos['txtNombre']))
          ->subject('Nuevo mensaje de contacto: ' . $asunto);
      });
    } catch (\Throwable $e) {
      Log::error('Error al enviar el formulario de contacto: ' . $e->getMessage());

      return back()->withInput()->with('error', 'No pudimos enviar el mensaje, intente nuevamente más tarde.');
    }

    return back()->with('success', '¡Mensaje enviado correctamente!');
  }
}
