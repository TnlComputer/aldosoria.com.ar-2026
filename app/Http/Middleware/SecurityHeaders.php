<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
  /**
   * Agrega cabeceras de seguridad a todas las respuestas.
   */
  public function handle(Request $request, Closure $next): Response
  {
    $response = $next($request);

    $headers = $response->headers;
    $headers->set('X-Content-Type-Options', 'nosniff');
    $headers->set('X-Frame-Options', 'SAMEORIGIN');
    $headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
    $headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), interest-cohort=()');
    $headers->set('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');
    $headers->set('Content-Security-Policy', "frame-ancestors 'self'; base-uri 'self'; object-src 'none'; upgrade-insecure-requests");
    $headers->remove('X-Powered-By');

    if ($request->isSecure()) {
      $headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
    }

    // Las paginas privadas no deben quedar guardadas en caches compartidas
    if ($request->user()) {
      $headers->set('Cache-Control', 'no-store, private');
    }

    return $response;
  }
}
