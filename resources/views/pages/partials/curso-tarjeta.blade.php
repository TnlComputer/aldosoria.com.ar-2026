{{-- Tarjeta de un curso. Variables: $curso (con simbolo de la moneda y, opcional, clases); $comprados (opcional) --}}
@php
  $detalle = route('cursoDetalle.show', $curso->id);
  $loTiene = in_array($curso->id, $comprados ?? []);
  $proximamente = $curso->coast < 0;
  $libre = !$proximamente && $curso->coast <= 1;
@endphp
<article @class(['tarjeta-medio', 'tarjeta-curso', 'tarjeta-curso-tuyo' => $loTiene])>
  <div class="tarjeta-curso-media">
    @if ($curso->video_demo_Uri)
    <x-sitio.video :url="$curso->video_demo_Uri" :titulo="$curso->name" :poster="$curso->image_curso ? \App\Models\Curso::urlImagen($curso->image_curso) : null" />
    @else
    <a href="{{ $detalle }}" tabindex="-1" aria-hidden="true">
      <img src="{{ \App\Models\Curso::urlImagen($curso->image_curso) }}" alt="" loading="lazy">
    </a>
    @endif
    @if ($loTiene)
    <span class="curso-etiqueta curso-etiqueta-tuyo">Ya lo tenés</span>
    @elseif ($proximamente)
    <span class="curso-etiqueta">Próximamente</span>
    @elseif ($libre)
    <span class="curso-etiqueta curso-etiqueta-libre">Gratis</span>
    @endif
  </div>
  <div class="tarjeta-medio-cuerpo">
    <h3><a href="{{ $detalle }}" class="tarjeta-curso-enlace">{{ $curso->name }}</a></h3>
    <p>{{ $curso->title }}</p>
    @if (!empty($curso->clases))
    <p class="tarjeta-curso-clases"><i class="fas fa-play-circle" aria-hidden="true"></i> {{ $curso->clases }} {{ $curso->clases == 1 ? 'clase' : 'clases' }}</p>
    @endif
    <div class="tarjeta-medio-pie">
      <span class="tarjeta-medio-precio">
        @if ($proximamente)
        Próximamente
        @elseif ($libre)
        Gratis
        @else
        {{ $curso->simbolo }} {{ number_format($curso->coast, 0, ',', '.') }}
        @endif
      </span>
      @if ($loTiene)
      <a href="{{ route('dashboard') }}" class="btn btn-sm btn-success">Ir al curso</a>
      @else
      <a href="{{ $detalle }}" class="btn btn-sm btn-primary">{{ $libre ? 'Ver curso' : 'Ver temario' }}</a>
      @endif
    </div>
  </div>
</article>
