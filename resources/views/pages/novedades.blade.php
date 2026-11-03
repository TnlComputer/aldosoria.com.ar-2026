@extends('layouts.app', ['page' => 'Novedades', 'pageSlug' => 'novedades'])

@section('content')
<div class="card">
  <div class="card-body">
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-3">
      <div>
        <h3 class="mb-1 text-white">Novedades</h3>
        <p class="mb-0" style="color: rgba(255,255,255,.65)">Trabajos, videos y lanzamientos del taller.</p>
      </div>
      <a href="https://www.youtube.com/channel/UCz1Q_SBgdqp_ULPY8poUM1g" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-secondary mt-2">
        <img src="{{ asset('black/img/social/youtube.svg') }}" alt="" width="18" height="18"> Suscribite en YouTube
      </a>
    </div>

    @if ($novedades->isEmpty())
    <p class="chip-vacio">Todavía no hay novedades publicadas.</p>
    @else
    <div class="tarjetas-medios">
      @foreach ($novedades as $novedad)
      @php($origen = ['1' => 'Facebook', '2' => 'Instagram', '3' => 'Twitter', '4' => 'YouTube'][(string) $novedad->tipo_novedad] ?? 'Web')
      <article class="tarjeta-medio">
        <x-sitio.video :url="$novedad->uri_novedad" :titulo="$novedad->novedades" />
        <div class="tarjeta-medio-cuerpo">
          <span class="tarjeta-medio-fecha">{{ \Carbon\Carbon::parse($novedad->fecha)->translatedFormat('d \d\e F \d\e Y') }} · {{ $origen }}</span>
          <h3>{{ $novedad->novedades }}</h3>
          @if ($origen !== 'YouTube')
          <div class="tarjeta-medio-pie">
            <a href="{{ $novedad->uri_novedad }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-secondary">Ver en {{ $origen }}</a>
          </div>
          @endif
        </div>
      </article>
      @endforeach
    </div>

    <div class="mt-4">{{ $novedades->links() }}</div>
    @endif
  </div>
</div>
@endsection
