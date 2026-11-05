@extends('layouts.app', [
  'page' => 'Cursos online de WinOLS y reprogramación',
  'pageSlug' => 'cursos',
  'description' => 'Cursos online de reprogramación de ECU con WinOLS: creación de mapas, pop & bang, EGR/DTC e inyección. Videos y archivos de trabajo, a tu ritmo.',
])

@section('content')
<div class="card">
  <div class="card-body">

    <x-sitio.hero titulo="Cursos online"
      texto="Aprendé reprogramación con WinOLS, creación de mapas, pop & bang e inyección, con videos y archivos de trabajo. Desde tu casa y a tu ritmo, con el respaldo de Soria Perfomance."
      whatsapp="Hola! Tengo una consulta sobre los cursos">
      @guest
      <p class="mb-2" style="font-size:.9rem">Para comprar un curso necesitás una cuenta: <a href="{{ route('register') }}">registrate</a> o <a href="{{ route('login') }}">ingresá</a>.</p>
      @else
      <a href="{{ route('dashboard') }}" class="btn btn-secondary">Mis cursos</a>
      @endguest
    </x-sitio.hero>

    <section class="pres-seccion">
      @if ($cursos->isEmpty())
      <p class="chip-vacio">Pronto vamos a publicar nuevos cursos.</p>
      @else
      <div class="tarjetas-medios">
        @foreach ($cursos as $curso)
        @include('pages.partials.curso-tarjeta')
        @endforeach
      </div>
      <div class="mt-4">{{ $cursos->links() }}</div>
      @endif
    </section>

    <section class="pres-seccion">
      <h2>Cómo funciona</h2>
      <ol class="pres-lista">
        <li>Elegí el curso y mirá su temario</li>
        <li>Pagá con PayPal o MercadoPago</li>
        <li>Lo ves desde "Mis cursos" apenas se acredita el pago</li>
      </ol>
    </section>
  </div>
</div>
@endsection
