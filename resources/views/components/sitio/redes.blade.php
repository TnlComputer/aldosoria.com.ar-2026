{{-- Botones a las redes del taller --}}
@php
  $redes = [
    ['YouTube', 'youtube.svg', 'https://www.youtube.com/channel/UCz1Q_SBgdqp_ULPY8poUM1g'],
    ['Instagram', 'instagram.svg', 'https://www.instagram.com/mecatronicasoria/'],
    ['Facebook', 'facebook.svg', 'https://www.facebook.com/www.aldosoria.com.ar/'],
    ['TikTok', 'tiktok.svg', 'https://www.tiktok.com/@perfomancesoria'],
  ];
@endphp
<div {{ $attributes->merge(['class' => 'sitio-redes']) }}>
  @foreach ($redes as [$nombre, $icono, $url])
  <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="sitio-red">
    <img src="{{ asset('black/img/social/' . $icono) }}" alt="" width="18" height="18"> {{ $nombre }}
  </a>
  @endforeach
</div>
