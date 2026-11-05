@extends('layouts.app', [
  'page' => 'Novedades',
  'pageSlug' => 'novedades',
  'description' => 'Videos y novedades de Soria Perfomance: trabajos de chiptuning, banco de pruebas, reprogramación y lanzamientos del taller.',
])

@php
  $origenes = ['1' => 'Facebook', '2' => 'Instagram', '3' => 'Twitter', '4' => 'YouTube'];
  $origenDe = fn ($n) => $origenes[(string) $n->tipo_novedad] ?? 'Web';
  $fechaDe = fn ($n) => \Carbon\Carbon::parse($n->fecha)->translatedFormat('j \d\e F \d\e Y');
  // En la primera pagina la novedad mas reciente va destacada
  $destacada = $novedades->onFirstPage() ? $novedades->first() : null;
  $resto = $destacada ? $novedades->slice(1) : $novedades;
@endphp

@section('content')
<div class="card">
  <div class="card-body">

    <x-sitio.hero titulo="Novedades"
      texto="Trabajos del taller, pruebas en el banco de potencia, reprogramaciones y lanzamientos de cursos. Seguinos para ver los videos apenas salen.">
      <x-sitio.redes />
    </x-sitio.hero>

    @if ($novedades->isEmpty())
    <p class="chip-vacio">Todavía no hay novedades publicadas.</p>
    @else

    @if ($destacada)
    <section class="pres-seccion novedad-destacada">
      <div class="novedad-destacada-media">
        @include('pages.partials.novedad-media', ['novedad' => $destacada, 'origen' => $origenDe($destacada)])
      </div>
      <div class="novedad-destacada-texto">
        <p class="curso-sobretitulo">Lo último</p>
        <h2>{{ $destacada->novedades }}</h2>
        <p class="tarjeta-medio-fecha">{{ $fechaDe($destacada) }} · {{ $origenDe($destacada) }}</p>
        <a href="https://wa.me/{{ config('services.whatsapp') }}?text={{ rawurlencode('Hola! Vi el video "' . $destacada->novedades . '" y quería consultar') }}" class="btn chip-whatsapp btn-sm" target="_blank" rel="noopener noreferrer">
          <img src="{{ asset('black/img/social/whatsapp.svg') }}" alt="" width="18" height="18"> Consultar por este trabajo
        </a>
      </div>
    </section>
    @endif

    @if ($resto->isNotEmpty())
    <section class="pres-seccion">
      @if ($destacada)
      <h2>Más videos</h2>
      @endif
      <div class="tarjetas-medios">
        @foreach ($resto as $novedad)
        @php($origen = $origenDe($novedad))
        <article class="tarjeta-medio">
          @include('pages.partials.novedad-media', ['novedad' => $novedad, 'origen' => $origen])
          <div class="tarjeta-medio-cuerpo">
            <span class="tarjeta-medio-fecha">{{ $fechaDe($novedad) }} · {{ $origen }}</span>
            <h3>{{ $novedad->novedades }}</h3>
          </div>
        </article>
        @endforeach
      </div>
    </section>
    @endif

    <div class="mt-4">{{ $novedades->links() }}</div>
    @endif

    <x-sitio.contacto titulo="¿Querés un trabajo así en tu auto?" mensaje="Hola! Vi sus videos y quería pedir un presupuesto" />
  </div>
</div>
@endsection
