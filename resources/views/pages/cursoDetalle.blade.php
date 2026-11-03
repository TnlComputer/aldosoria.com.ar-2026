@extends('layouts.app', ['page' => __('Curso Detalle'), 'pageSlug' => 'cursos'])
@section('content')
<div class="card">
  <div class="card-body">
    <div class="chart-area">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-6">
            <div class="font-cuadro-detail p-0">
              <div class="bg-white rounded-top white-content">
                <h4 class="p-2 resalta">TEMARIO</h4>
              </div>
              @if (count($temarios) > 0)
              <ol class="text-left">
                @foreach ($temarios as $temario)
                <li class="p-2">
                  {{-- {{ $temarios->count() }} --}}
                  {{ $temario->descripcion }}
                </li>
                @endforeach
              </ol>
              @else
              <div>
                <p class="m-5 text-white">El curso no dispone de temario</p>
              </div>
              @endif
            </div>
          </div>
          <div class="col-md-6">
            <div class="align-items-center font-cuadro-detail p-0">
              @if ($curso->coast > 0)
              <div class=" cuadro-color-detalle rounded-top white-content">
                <h4 class="p-2 resalta">PAGO</h4>
              </div>
              <form action="{{ route('cursoDetalle.pago') }}" method="POST">
                @csrf
                <div class="py-4 text-right">
                  <div class="row">
                    <div class="col-8 text-white">
                      <div class=" text-white text-left ml-4"> Detalle</div>
                    </div>
                    <div class="col-4 text-white">
                      <div class=" text-white text-center mr-4"> Importe</div>
                    </div>
                  </div>
                  <div class="text-white text-left ml-4 mt-2">{{ $curso->name }} </div>
                  <div class="text-white text-left ml-4 mt-1"> {{ $curso->title }} </div>
                  <div class="row py-4">
                    <div class="col-8 text-white">
                      <div class=" text-white text-right ml-4"> Total</div>
                    </div>
                    <div class="col-4 text-white">
                      <div class=" text-center mr-4 importe"> {{ $moneda->simbolo }}
                        {{ $curso->coast }}
                      </div>
                    </div>
                  </div>
                </div>
                @Auth
                <div>
                  <div class="col-10 text-left ml-2">
                    <h4>Método de pago</h4>
                  </div>
                  <div class="row text-left ml-2">
                    <div class="col-10 text-white pb-2">
                      <input type="radio" name="metodoPago" id="pa" value="pa" checked>
                      <input type="hidden" name="curso_id" id="curso_id" value="{{ $curso->id }}">
                      <label for="pa">PayPal</label>
                    </div>
                    @if (Auth::user()->pais_id == 9)
                    <div class="col-10 text-white pb-2">
                      <input type="radio" name="metodoPago" id="mp" value="mp">
                      <label for="mp">Mercado Pago</label>
                    </div>
                    @endif
                  </div>
                </div>
                @endif
                <div class="text-right m-4">
                  <input class=" btn text-white w-100 " type="submit" value="COMPRAR CURSO">
                </div>
              </form>
              @endif
            </div>
          </div>
        </div>
      </div>
      <div class="row ">
        <div class=" col-md-12 text-center">
          <a class="btn btn-outline-default w-100" href="{{ route('cursos') }}">Volver a Cursos</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection