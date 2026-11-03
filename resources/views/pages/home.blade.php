@extends('layouts.app', ['page' => 'Inicio', 'pageSlug' => 'home'])

@section('content')
<div class="card">
  <div class="card-body">

    <x-sitio.hero titulo="Mecánica, electrónica y chiptuning en Zona Oeste"
      texto="Taller familiar con más de 27 años en Morón. Reparación y mantenimiento, electrónica automotor, reprogramación de ECU con banco de rodillos y cursos online de WinOLS."
      whatsapp="Hola! Quisiera más información" :imagen="asset('black/img/soria perfomance auto.png')">
      <a href="{{ route('chipMarcas') }}" class="btn btn-primary"><i class="tim-icons icon-zoom-split"></i> ¿Cuánto gana tu auto?</a>
      <a href="{{ route('cursos') }}" class="btn btn-secondary"><i class="tim-icons icon-video-66"></i> Cursos online</a>
      <x-slot:datos>
        <div class="pres-dato"><strong>27 años</strong><span>en la Zona Oeste</span></div>
        <div class="pres-dato"><strong>300 km/h</strong><span>de prueba en banco de rodillos</span></div>
        <div class="pres-dato"><strong>{{ $fichas }}</strong><span>vehículos reprogramados de {{ $marcas }} marcas</span></div>
        <div class="pres-dato"><strong>EVC · NAPRO</strong><span>representantes oficiales</span></div>
      </x-slot:datos>
    </x-sitio.hero>

    <section class="pres-seccion">
      <h2>Nuestros servicios</h2>
      <div class="pres-servicios">
        <a class="pres-servicio" href="{{ route('mecanica') }}">
          <img src="{{ asset('black/img/Mecanica/mecanica1.jpg') }}" alt="" loading="lazy">
          <div class="pres-servicio-cuerpo">
            <h3>Mecánica</h3>
            <p>Motores, tapas de cilindros, frenos y ABS, tren delantero, distribución, amortiguación y electricidad. Nafta y diésel.</p>
            <span class="pres-mas">Ver más →</span>
          </div>
        </a>
        <a class="pres-servicio" href="{{ route('electronica') }}">
          <img src="{{ asset('black/img/Electronica/13607046.jpg') }}" alt="" loading="lazy">
          <div class="pres-servicio-cuerpo">
            <h3>Electrónica</h3>
            <p>Laboratorio propio: inyección, ABS, airbag, pin code, codificación de llaves e inmovilizadores.</p>
            <span class="pres-mas">Ver más →</span>
          </div>
        </a>
        <a class="pres-servicio" href="{{ route('chiptuning') }}">
          <img src="{{ asset('black/img/evc-car.jpg') }}" alt="" loading="lazy">
          <div class="pres-servicio-cuerpo">
            <h3>Chiptuning</h3>
            <p>Reprogramación Stage 1, 2 y 3, cajas DSG y Launch Control. Medido antes y después en banco de rodillos.</p>
            <span class="pres-mas">Buscá tu vehículo →</span>
          </div>
        </a>
        <a class="pres-servicio" href="{{ route('cursos') }}">
          <img src="{{ asset('img/cursos/Winols 5.png') }}" alt="" loading="lazy">
          <div class="pres-servicio-cuerpo">
            <h3>Cursos online</h3>
            <p>Aprendé WinOLS, mapas, pop &amp; bang e inyección con videos y archivos, a tu ritmo.</p>
            <span class="pres-mas">Ver cursos →</span>
          </div>
        </a>
        <a class="pres-servicio" href="{{ route('mantenimiento') }}">
          <img src="{{ asset('black/img/mantenimiento-programado.jpg') }}" alt="" loading="lazy">
          <div class="pres-servicio-cuerpo">
            <h3>Mantenimiento programado</h3>
            <p>Te avisamos por email cuándo cambiar aceite y filtros, y revisamos los 11 puntos clave de tu auto.</p>
            <span class="pres-mas">Ver más →</span>
          </div>
        </a>
        <a class="pres-servicio" href="{{ route('representacion') }}">
          <img src="{{ asset('black/img/logosRepre5.png') }}" alt="" loading="lazy" style="object-fit:contain;background:#fff">
          <div class="pres-servicio-cuerpo">
            <h3>Representación oficial</h3>
            <p>Únicos representantes en Latinoamérica de EVC (WinOLS) y del escáner NAPRO de Brasil.</p>
            <span class="pres-mas">Ver equipos →</span>
          </div>
        </a>
      </div>
    </section>

    @if ($cursos->isNotEmpty())
    <section class="pres-seccion">
      <div class="d-flex flex-wrap justify-content-between align-items-end mb-2">
        <h2 class="mb-2">Últimos cursos</h2>
        <a href="{{ route('cursos') }}" class="btn btn-sm btn-secondary mb-2">Ver todos los cursos</a>
      </div>
      <div class="tarjetas-medios">
        @foreach ($cursos as $curso)
        @include('pages.partials.curso-tarjeta')
        @endforeach
      </div>
    </section>
    @endif

    <section class="pres-seccion">
      <h2>El taller</h2>
      <div class="pres-galeria">
        <img loading="lazy" src="{{ asset('black/img/Taller/20170215_140322.jpg') }}" alt="Taller Soria Perfomance">
        <img loading="lazy" src="{{ asset('black/img/BancoPrueba/20170215_140315.jpg') }}" alt="Banco de rodillos">
        <img loading="lazy" src="{{ asset('black/img/Laboratorio/20170215_140217.jpg') }}" alt="Laboratorio electrónico">
        <img loading="lazy" src="{{ asset('black/img/Mecanica/mecanica2.jpg') }}" alt="Trabajos de mecánica">
      </div>
      <p>Banco de rodillos para probar el vehículo a alta velocidad sin salir del taller, laboratorio electrónico propio y sala de espera para que esperes tu trabajo o presupuesto con comodidad.</p>
    </section>

    @if ($novedades->isNotEmpty())
    <section class="pres-seccion">
      <div class="d-flex flex-wrap justify-content-between align-items-end mb-2">
        <h2 class="mb-2">Novedades</h2>
        <a href="{{ route('novedad') }}" class="btn btn-sm btn-secondary mb-2">Ver todas</a>
      </div>
      <div class="tarjetas-medios">
        @foreach ($novedades as $novedad)
        <article class="tarjeta-medio">
          <x-sitio.video :url="$novedad->uri_novedad" :titulo="$novedad->novedades" />
          <div class="tarjeta-medio-cuerpo">
            <span class="tarjeta-medio-fecha">{{ \Carbon\Carbon::parse($novedad->fecha)->translatedFormat('d \d\e F \d\e Y') }}</span>
            <h3>{{ $novedad->novedades }}</h3>
          </div>
        </article>
        @endforeach
      </div>
    </section>
    @endif

    <x-sitio.contacto />
  </div>
</div>
@endsection
