@php
  $proximamente = $curso->coast < 0;
  $libre = !$proximamente && $curso->coast <= 1;
  $simbolo = $moneda->simbolo ?? '';
  $videos = $temarios->where('tipo_curso', '!=', 2)->count();
  $archivos = $temarios->where('tipo_curso', 2)->count();
  $imagen = \App\Models\Curso::urlImagen($curso->image_curso);
  $consulta = 'Hola! Tengo una consulta sobre el curso ' . $curso->name;
@endphp
@extends('layouts.app', [
  'page' => $curso->name,
  'pageSlug' => 'cursos',
  'ogImage' => $imagen,
  'description' => \Illuminate\Support\Str::limit($curso->name . ': ' . $curso->title . '. Curso online de Soria Perfomance con videos' . ($archivos ? ' y archivos de trabajo' : '') . '.', 155),
])

@push('seo')
<script type="application/ld+json">
{!! json_encode(array_filter([
  '@context' => 'https://schema.org',
  '@type' => 'Course',
  'name' => $curso->name,
  'description' => trim($curso->name . '. ' . $curso->title),
  'image' => $imagen,
  'inLanguage' => 'es',
  'provider' => ['@type' => 'Organization', 'name' => 'Soria Perfomance', 'sameAs' => url('/')],
  'hasCourseInstance' => ['@type' => 'CourseInstance', 'courseMode' => 'online'],
  'offers' => (!$proximamente && !$libre && $moneda) ? [
    '@type' => 'Offer',
    'price' => (string) $curso->coast,
    'priceCurrency' => strtoupper($moneda->moneda ?? 'EUR'),
    'availability' => 'https://schema.org/InStock',
    'url' => url()->current(),
    'category' => 'Paid',
  ] : null,
]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}
</script>
@endpush

@section('content')
<div class="card">
  <div class="card-body">

    <a href="{{ route('cursos') }}" class="curso-volver"><i class="fas fa-arrow-left" aria-hidden="true"></i> Todos los cursos</a>

    <div class="curso-cabecera">
      <div class="curso-media">
        @if ($curso->video_demo_Uri)
        <x-sitio.video :url="$curso->video_demo_Uri" :titulo="'Presentación: ' . $curso->name" :poster="$curso->image_curso ? $imagen : null" />
        <p class="curso-media-nota"><i class="fas fa-play" aria-hidden="true"></i> Video de presentación</p>
        @else
        <img src="{{ $imagen }}" alt="{{ $curso->name }}" fetchpriority="high">
        @endif
      </div>

      <div class="curso-info">
        <p class="curso-sobretitulo">Curso online</p>
        <h1>{{ $curso->name }}</h1>
        @if ($curso->title)
        <p class="curso-subtitulo">{{ $curso->title }}</p>
        @endif

        <ul class="curso-datos">
          @if ($videos)
          <li><i class="fas fa-play-circle" aria-hidden="true"></i> {{ $videos }} {{ $videos == 1 ? 'clase en video' : 'clases en video' }}</li>
          @endif
          @if ($archivos)
          <li><i class="fas fa-download" aria-hidden="true"></i> {{ $archivos }} {{ $archivos == 1 ? 'entrega con archivos' : 'entregas con archivos' }}</li>
          @endif
          @unless ($libre || $proximamente)
          <li><i class="fas fa-clock" aria-hidden="true"></i> {{ $diasAcceso }} días de acceso</li>
          @endunless
          <li><i class="fas fa-laptop" aria-hidden="true"></i> Online, a tu ritmo</li>
        </ul>

        {{-- Compra --}}
        <div class="curso-compra">
          @if ($accesoHasta)
          <p class="curso-compra-estado"><i class="fas fa-check-circle" aria-hidden="true"></i> Ya tenés este curso, disponible hasta el {{ $accesoHasta->format('d/m/Y') }}.</p>
          <a href="{{ route('dashboard') }}" class="btn btn-success btn-block">Ir a Mis cursos</a>

          @elseif ($proximamente)
          <p class="curso-precio">Próximamente</p>
          <p class="curso-compra-nota">Todavía no está a la venta. Escribinos y te avisamos cuando salga.</p>

          @elseif ($libre)
          <p class="curso-precio">Gratis</p>
          @if ($curso->video_demo_Uri)
          <p class="curso-compra-nota">Miralo completo en el video de esta página.</p>
          @endif

          @else
          <p class="curso-precio"><small>{{ $simbolo }}</small> {{ number_format($curso->coast, 0, ',', '.') }}</p>
          @auth
          <form action="{{ route('cursoDetalle.pago') }}" method="POST">
            @csrf
            <input type="hidden" name="curso_id" value="{{ $curso->id }}">
            <fieldset class="curso-metodos">
              <legend>Elegí cómo pagar</legend>
              <label class="curso-metodo">
                <input type="radio" name="metodoPago" value="pa" checked>
                <img src="{{ asset('black/img/Paypal.png') }}" alt="" width="88" height="56">
                <span>PayPal <small>tarjeta o cuenta PayPal</small></span>
              </label>
              @if (Auth::user()->pais_id == 9)
              <label class="curso-metodo">
                <input type="radio" name="metodoPago" value="mp">
                <img src="{{ asset('black/img/mercadoPago1.png') }}" alt="" width="88" height="56">
                <span>Mercado Pago <small>pesos argentinos</small></span>
              </label>
              @else
              {{-- Mercado Pago solo cobra en pesos: se muestra, pero solo para cuentas de Argentina --}}
              <div class="curso-metodo curso-metodo-off" aria-disabled="true">
                <input type="radio" disabled aria-label="Mercado Pago (solo Argentina)">
                <img src="{{ asset('black/img/mercadoPago1.png') }}" alt="" width="88" height="56">
                <span>Mercado Pago <small>Solo para cuentas de Argentina. <a href="{{ route('profile.edit') }}">Cambiar el país en mi perfil</a></small></span>
              </div>
              @endif
            </fieldset>
            <button type="submit" class="btn btn-primary btn-block btn-lg">Comprar curso</button>
          </form>
          @else
          <p class="curso-compra-nota">Para comprar necesitás una cuenta. Es gratis y tarda un minuto.</p>
          <a href="{{ route('register') }}" class="btn btn-primary btn-block">Crear cuenta</a>
          <a href="{{ route('login') }}" class="btn btn-outline-default btn-block">Ya tengo cuenta</a>
          @endauth
          @endif

          <a href="https://wa.me/{{ config('services.whatsapp') }}?text={{ rawurlencode($consulta) }}" class="curso-consulta" target="_blank" rel="noopener noreferrer">
            <img src="{{ asset('black/img/social/whatsapp.svg') }}" alt="" width="18" height="18"> ¿Dudas? Consultanos por WhatsApp
          </a>
        </div>
      </div>
    </div>

    <section class="pres-seccion">
      <h2>Temario</h2>
      @if ($temarios->isEmpty())
      <p class="chip-vacio">El temario de este curso se publica pronto.</p>
      @else
      <ol class="curso-temario">
        @foreach ($temarios as $temario)
        <li>
          <span class="curso-temario-nro">{{ $loop->iteration }}</span>
          <span class="curso-temario-texto">{{ $temario->descripcion }}</span>
          @if ($temario->tipo_curso == 2)
          <span class="curso-temario-tipo"><i class="fas fa-download" aria-hidden="true"></i> Archivos</span>
          @else
          <span class="curso-temario-tipo"><i class="fas fa-play-circle" aria-hidden="true"></i> Video</span>
          @endif
        </li>
        @endforeach
      </ol>
      @endif
    </section>

    @unless ($libre || $proximamente || $accesoHasta)
    <section class="pres-seccion">
      <h2>Cómo funciona</h2>
      <ol class="pres-lista">
        <li>Pagá con PayPal{{ Auth::check() && Auth::user()->pais_id != 9 ? '' : ' o Mercado Pago' }}</li>
        <li>Apenas se acredita el pago, el curso aparece en "Mis cursos"</li>
        <li>Tenés {{ $diasAcceso }} días para ver las clases{{ $archivos ? ' y descargar los archivos' : '' }} las veces que quieras</li>
      </ol>
    </section>
    @endunless

  </div>
</div>
@endsection
