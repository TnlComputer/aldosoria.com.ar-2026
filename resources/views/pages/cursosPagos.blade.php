@extends('layouts.app', ['page' => __('Tus Cursos'), 'pageSlug' => 'cursosPagos'])
@section('content')
<div class="card px-3">
  <div class="font-icon-detail ">
    <span class="row mx-3 ">
      <form action="{{ route('dashboard') }}">
        <input type="submit" value="Volver al Dashboard" class="btn" />
      </form>
    </span>
    @if ($cursoPago->isNotEmpty())
    @php($tit = $titulos->first())
    <h3>{{ $tit->nombre_curso }} - {{ $tit->nombre_video }} </h3>
    <div class="row justify-content-between mt-4 text-center">
      @foreach ($cursoPago as $video)
      <div class="col-sm-12 col-md-6 pb-4 shadow-lg">
        <h4> {{ $video->descripcion }} </h4>
        <div class="col-lg-12 ">
          @if ($video->uri_video)
          <iframe src="{{ $video->uri_video }}" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" width="100%" height="auto" webkitAllowFullScreen mozallowfullscreen
            allowfullscreen frameborder="1" scrolling="no"></iframe>
          @else
          @if ($video->tipo_curso == 2)
          @if ($video->archivo1_curso)
          <a class="btn" href="{{ route('curso.archivo', [$video->id, 1]) }}" rel="nofollow">Descargar
          </a>
          @endif
          @if ($video->archivo2_curso)
          <a class="btn" href="{{ route('curso.archivo', [$video->id, 2]) }}" rel="nofollow">Descargar
          </a>
          @endif
          @endif
          @endif
        </div>
      </div>
      @endforeach
    </div>
    <div class="col-lg-12 justify-content-right">
      <div class="justify-content-right">
        {{ $cursoPago->links() }}
      </div>
    </div>
    @else
    <p class="text-white">No tienes cursos disponibles</p>
    @endif
  </div>
</div>

@endsection