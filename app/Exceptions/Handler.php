<?php

namespace App\Exceptions;

use App\Support\Subidas;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Archivo mas grande de lo que admite el servidor: volver al formulario con un aviso claro
        $this->renderable(function (PostTooLargeException $e, $request) {
            $mensaje = 'El archivo es demasiado grande. El máximo permitido es ' . Subidas::maximoArchivoMb() . ' MB.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $mensaje], 413);
            }

            return redirect()->back()->with('error', $mensaje);
        });
    }
}
