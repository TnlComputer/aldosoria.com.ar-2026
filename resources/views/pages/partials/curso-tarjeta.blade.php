{{-- Tarjeta de un curso. Variable: $curso (con simbolo de la moneda) --}}
<article class="tarjeta-medio">
  @if ($curso->video_demo_Uri)
  <x-sitio.video :url="$curso->video_demo_Uri" :titulo="$curso->name" :poster="$curso->image_curso ? \App\Models\Curso::urlImagen($curso->image_curso) : null" />
  @else
  <a href="{{ route('cursoDetalle.show', $curso->id) }}">
    <img src="{{ \App\Models\Curso::urlImagen($curso->image_curso) }}" alt="{{ $curso->name }}" loading="lazy" style="width:100%;aspect-ratio:16/9;object-fit:cover">
  </a>
  @endif
  <div class="tarjeta-medio-cuerpo">
    <h3>{{ $curso->name }}</h3>
    <p>{{ $curso->title }}</p>
    <div class="tarjeta-medio-pie">
      <span class="tarjeta-medio-precio">
        @if ($curso->coast < 0)
        Próximamente
        @elseif ($curso->coast <= 1)
        Libre
        @else
        {{ $curso->simbolo }} {{ number_format($curso->coast, 0, ',', '.') }}
        @endif
      </span>
      @if ($curso->coast > 1)
      <a href="{{ route('cursoDetalle.show', $curso->id) }}" class="btn btn-sm btn-primary">Ver temario</a>
      @endif
    </div>
  </div>
</article>
