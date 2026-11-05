{{--
  Etiquetas para buscadores y redes. Cada pagina puede pasar en @extends:
  page (titulo corto), title (titulo completo), description, canonical, ogImage, ogType, noindex
--}}
@php
  $seoTitulo = $title ?? (isset($page) ? $page . ' | Soria Perfomance' : 'Soria Perfomance');
  $seoDescripcion = $description ?? 'Soria Perfomance: chiptuning, reprogramación de ECU, banco de rodillos, mecánica y electrónica automotriz en Morón, Zona Oeste de Buenos Aires. Cursos online de WinOLS.';
  $seoCanonical = $canonical ?? url()->current();
  $seoImagen = $ogImage ?? asset('img/inicio/og-soria.jpg');
  $seoPrivada = !empty($noindex) || request()->is('dashboard*', 'profile*', 'mercadopago*', 'curso/*', 'thanks', 'failed');
@endphp
<title>{{ $seoTitulo }}</title>
<meta name="description" content="{{ $seoDescripcion }}">
<link rel="canonical" href="{{ $seoCanonical }}">
@if ($seoPrivada)
<meta name="robots" content="noindex, nofollow">
@else
<meta name="robots" content="index, follow, max-image-preview:large">
@endif

<meta property="og:site_name" content="Soria Perfomance">
<meta property="og:locale" content="es_AR">
<meta property="og:type" content="{{ $ogType ?? 'website' }}">
<meta property="og:title" content="{{ $seoTitulo }}">
<meta property="og:description" content="{{ $seoDescripcion }}">
<meta property="og:url" content="{{ $seoCanonical }}">
<meta property="og:image" content="{{ $seoImagen }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoTitulo }}">
<meta name="twitter:description" content="{{ $seoDescripcion }}">
<meta name="twitter:image" content="{{ $seoImagen }}">
<meta name="theme-color" content="#111317">

@if (request()->routeIs('home', 'contacto'))
{{-- Ficha del negocio para Google (mapa, telefono y direccion en la busqueda local) --}}
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'AutoRepair',
  '@id' => url('/') . '#taller',
  'name' => 'Soria Perfomance',
  'alternateName' => 'Soria Performance',
  'description' => 'Taller de mecánica, electrónica automotriz y chiptuning con banco de rodillos en Morón, Buenos Aires.',
  'url' => url('/'),
  'logo' => asset('black/img/logoSP1.png'),
  'image' => [asset('img/inicio/taller-frente.webp'), asset('img/inicio/taller-banco.webp')],
  'telephone' => '+54 11 4483-1235',
  'email' => 'info@aldosoria.com.ar',
  'address' => [
    '@type' => 'PostalAddress',
    'streetAddress' => 'General A. J. de Sucre 1053/75',
    'addressLocality' => 'Morón',
    'addressRegion' => 'Buenos Aires',
    'postalCode' => '1708',
    'addressCountry' => 'AR',
  ],
  'geo' => ['@type' => 'GeoCoordinates', 'latitude' => -34.659922, 'longitude' => -58.631064],
  'areaServed' => ['Morón', 'Zona Oeste', 'Gran Buenos Aires'],
  'sameAs' => [
    'https://www.facebook.com/www.aldosoria.com.ar/',
    'https://www.instagram.com/mecatronicasoria/',
    'https://www.youtube.com/channel/UCz1Q_SBgdqp_ULPY8poUM1g',
    'https://www.tiktok.com/@perfomancesoria',
  ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}
</script>
@endif
@stack('seo')
