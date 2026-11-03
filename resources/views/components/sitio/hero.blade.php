@props(['titulo', 'texto' => null, 'whatsapp' => null, 'imagen' => null])

<div @class(['pres-hero', 'pres-hero-con-imagen' => $imagen])>
  <div class="pres-hero-texto">
    <h1>{{ $titulo }}</h1>
    @if ($texto)
    <p>{{ $texto }}</p>
    @endif
    {{ $slot }}
    @if ($whatsapp)
    <a href="https://wa.me/{{ config('services.whatsapp') }}?text={{ rawurlencode($whatsapp) }}" class="btn chip-whatsapp" target="_blank" rel="noopener noreferrer">
      <img src="{{ asset('black/img/social/whatsapp.svg') }}" alt="" width="20" height="20"> Consultar por WhatsApp
    </a>
    @endif
  </div>
  @if ($imagen)
  <img class="pres-hero-imagen" src="{{ $imagen }}" alt="" fetchpriority="high">
  @endif
  @isset($datos)
  <div class="pres-datos">{{ $datos }}</div>
  @endisset
</div>
