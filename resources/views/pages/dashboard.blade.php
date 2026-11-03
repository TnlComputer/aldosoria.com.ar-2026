@extends('layouts.app', ['page' => __('Dashboard'), 'pageSlug' => 'dashboard'])
@section('content')
<div class="row">
  <div class="card">
    <div class="card-body">
      <div class="chart-area">
        <div class=" text-white">
          <div class="d-inline">PANEL PERSONAL - {{ Auth::user()->name }} {{ Auth::user()->last_name }}
          </div>
          <div class="row">
            @if (Auth::user()->roles == 'ROLE_USER')
            <div class="card px-3">
              <div class="row justify-content-center mt-4 text-center">
                <div class="col-lg-12 ">
                  @if (!Is_null($cursosPagos))
                  @foreach ($cursosPagos as $cursoPago)
                  <div class="font-icon-detail">
                    <div class="row">
                      <div class=" col-md-6">
                        <h4>{{ $cursoPago->name }} - {{ $cursoPago->title }} </h4>
                        <div class="col-lg-12">
                          <img loading="lazy" class="w-100 rounded" src="{{ \App\Models\Curso::urlImagen($cursoPago->image_curso) }}" alt="{{ $cursoPago->name }}">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <h4>Temario</h4>
                        <div class="col-md-12 text-left text-white">
                          @if (!Is_null($cursoPago))
                          @foreach ($temarios as $temario)
                          @if ($cursoPago->video_curso_id == $temario->video_curso_id)
                          <div class="pb-2">{{ $temario->descripcion }}</div>
                          @endif
                          @endforeach
                          @else
                          <h5 class="my-3 mx-4">No hay Temario disponible para este Curso</h5>
                          @endif
                        </div>
                      </div>
                    </div>
                    <div class="mt-4 ">
                      @php($vto = \Carbon\Carbon::parse($cursoPago->vto_curso))
                      @if ($vto->endOfDay()->isPast())
                      <p class="text-warning">El acceso a este curso venció el {{ $vto->format('d-m-Y') }}.<br>
                        Para renovarlo escribinos a {{ config('mail.admin_address') }}</p>
                      @else
                      <p class="small">Disponible hasta el {{ $vto->format('d-m-Y') }}</p>
                      <a class="btn btn-group-lg border-black" href="{{ route('video.show', $cursoPago->video_curso_id) }}">Ver videos del curso</a>
                      @endif
                    </div>
                  </div>
                  @endforeach
                  @else
                  <p class=" text-white">No tienes cursos disponibles</p>
                  @endif
                  @if (Auth::user()->roles == 'ROLE_USER')
                  <div class="px-4 py-2">
                    {{ $cursosPagos->links() }}
                  </div>
                  @endif
                </div>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection