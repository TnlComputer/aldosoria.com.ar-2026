@props(['titulo', 'subtitulo' => null, 'volver' => null])

@php
  $secciones = [
    ['dashboard', 'dashboard', 'icon-chart-pie-36', 'Inicio'],
    ['cursos.index', 'cursos.*|temario.*', 'icon-video-66', 'Cursos'],
    ['pagos.index', 'pagos.*', 'icon-coins', 'Pagos'],
    ['usuarios.index', 'usuarios.*', 'icon-single-02', 'Usuarios'],
    ['chiptuning.index', 'chiptuning.*', 'icon-spaceship', 'Chiptuning'],
    ['marcas.index', 'marcas.*', 'icon-badge', 'Marcas'],
    ['novedad.index', 'novedad.*', 'icon-paper', 'Novedades'],
    ['adminOferta.index', 'adminOferta.*', 'icon-gift-2', 'Oferta'],
    ['monedas.index', 'monedas.*', 'icon-money-coins', 'Monedas'],
  ];
@endphp

<div class="admin">
  <nav class="admin-secciones" aria-label="Secciones de administración">
    @foreach ($secciones as [$ruta, $patron, $icono, $texto])
    <a href="{{ route($ruta) }}" @class(['activa' => request()->routeIs(...explode('|', $patron))])>
      <i class="tim-icons {{ $icono }}"></i> {{ $texto }}
    </a>
    @endforeach
  </nav>

  <div class="card admin-card">
    <div class="admin-encabezado">
      <div class="admin-encabezado-texto">
        @if ($volver)
        <a href="{{ $volver }}" class="admin-volver"><i class="tim-icons icon-minimal-left"></i> Volver</a>
        @endif
        <h3 class="admin-titulo">{{ $titulo }}</h3>
        @if ($subtitulo)
        <p class="admin-subtitulo">{{ $subtitulo }}</p>
        @endif
      </div>
      @isset($acciones)
      <div class="admin-acciones">{{ $acciones }}</div>
      @endisset
    </div>

    <x-admin.alertas />

    {{ $slot }}
  </div>
</div>
