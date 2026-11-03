@props(['url', 'titulo' => 'Video', 'poster' => null])

@php
  // Videos de YouTube: se muestra la miniatura y el reproductor se carga recien al tocar play
  $id = preg_match('~(?:youtube(?:-nocookie)?\.com/(?:embed/|watch\?v=|shorts/)|youtu\.be/)([A-Za-z0-9_-]{11})~', (string) $url, $m) ? $m[1] : null;
@endphp

@if ($id)
<button type="button" class="video-liviano" data-video="https://www.youtube-nocookie.com/embed/{{ $id }}?autoplay=1&rel=0"
  aria-label="Reproducir: {{ $titulo }}" {{ $attributes }}>
  <img src="{{ $poster ?: 'https://i.ytimg.com/vi/' . $id . '/hqdefault.jpg' }}" alt="" loading="lazy" width="480" height="360">
  <span class="video-liviano-play" aria-hidden="true"></span>
</button>
@elseif ($url)
<iframe src="{{ $url }}" title="{{ $titulo }}" loading="lazy" allowfullscreen allow="autoplay; encrypted-media; picture-in-picture" {{ $attributes }}></iframe>
@endif
