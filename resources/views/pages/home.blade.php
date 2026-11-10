@extends('layouts.app', [
  'page' => 'Inicio',
  'pageSlug' => 'home',
  'title' => 'Soria Perfomance | Chiptuning, mecánica y electrónica en Morón',
  'description' => 'Taller familiar en Morón con más de 27 años: chiptuning con banco de rodillos, reprogramación de ECU, mecánica y electrónica automotriz en Zona Oeste. Cursos online de WinOLS.',
])

@section('content')
<div class="card">
  <div class="card-body">

    <x-sitio.hero titulo="Mecánica, electrónica y chiptuning en Zona Oeste"
      texto="Taller familiar con más de 27 años en Morón. Reparación y mantenimiento, electrónica automotor, reprogramación de ECU con banco de rodillos y cursos online de WinOLS."
      whatsapp="Hola! Quisiera más información" :imagen="asset('img/inicio/hero-auto.webp')" imagen-alt="Auto con el logo de Soria Perfomance">
      <a href="{{ route('chipMarcas') }}" class="btn btn-primary"><i class="tim-icons icon-zoom-split"></i> ¿Cuánto gana tu auto?</a>
      <a href="{{ route('cursos') }}" class="btn btn-secondary"><i class="tim-icons icon-video-66"></i> Cursos online</a>
      <x-slot:datos>
        <div class="pres-dato"><strong>27 años</strong><span>en la Zona Oeste</span></div>
        <div class="pres-dato"><strong>300 km/h</strong><span>de prueba en banco de rodillos</span></div>
        <div class="pres-dato"><strong>{{ $fichas }}</strong><span>vehículos reprogramados de {{ $marcas }} marcas</span></div>
        <div class="pres-dato"><strong>NAPRO</strong><span>representantes oficiales</span></div>
      </x-slot:datos>
    </x-sitio.hero>

    <section class="pres-seccion">
      <h2>Nuestros servicios</h2>
      <div class="pres-servicios">
        <a class="pres-servicio" href="{{ route('mecanica') }}">
          <img src="{{ asset('img/inicio/servicio-mecanica.webp') }}" alt="" loading="lazy" width="800" height="450">
          <div class="pres-servicio-cuerpo">
            <h3>Mecánica</h3>
            <p>Motores, tapas de cilindros, frenos y ABS, tren delantero, distribución, amortiguación y electricidad. Nafta y diésel.</p>
            <span class="pres-mas">Ver más →</span>
          </div>
        </a>
        <a class="pres-servicio" href="{{ route('electronica') }}">
          <img src="{{ asset('img/inicio/servicio-electronica.webp') }}" alt="" loading="lazy" width="800" height="450">
          <div class="pres-servicio-cuerpo">
            <h3>Electrónica</h3>
            <p>Laboratorio propio: inyección, ABS, airbag, pin code, codificación de llaves e inmovilizadores.</p>
            <span class="pres-mas">Ver más →</span>
          </div>
        </a>
        <a class="pres-servicio" href="{{ route('chiptuning') }}">
          <img src="{{ asset('img/inicio/servicio-chiptuning.webp') }}" alt="" loading="lazy" width="800" height="450">
          <div class="pres-servicio-cuerpo">
            <h3>Chiptuning</h3>
            <p>Reprogramación Stage 1, 2 y 3, cajas DSG y Launch Control. Medido antes y después en banco de rodillos.</p>
            <span class="pres-mas">Buscá tu vehículo →</span>
          </div>
        </a>
        <a class="pres-servicio" href="{{ route('cursos') }}">
          <img src="{{ asset('img/inicio/servicio-cursos.webp') }}" alt="" loading="lazy" width="800" height="450">
          <div class="pres-servicio-cuerpo">
            <h3>Cursos online</h3>
            <p>Aprendé WinOLS, mapas, pop &amp; bang e inyección con videos y archivos, a tu ritmo.</p>
            <span class="pres-mas">Ver cursos →</span>
          </div>
        </a>
        <a class="pres-servicio" href="{{ route('mantenimiento') }}">
          <img src="{{ asset('img/inicio/servicio-mantenimiento.webp') }}" alt="" loading="lazy" width="800" height="450">
          <div class="pres-servicio-cuerpo">
            <h3>Mantenimiento programado</h3>
            <p>Te avisamos por email cuándo cambiar aceite y filtros, y revisamos los 11 puntos clave de tu auto.</p>
            <span class="pres-mas">Ver más →</span>
          </div>
        </a>
        <a class="pres-servicio" href="{{ route('representacion') }}">
          <img src="{{ asset('black/img/napro.jpg') }}" alt="" loading="lazy" style="object-fit:contain;background:#fff">
          <div class="pres-servicio-cuerpo">
            <h3>Representación oficial</h3>
            <p>Únicos autorizados en el país para vender el escáner de diagnóstico NAPRO de Brasil.</p>
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
      <h2>Así trabajamos el chiptuning</h2>
      <ol class="pres-lista inicio-pasos">
        <li><strong>Medición inicial</strong> en el banco de rodillos: potencia y torque reales de tu auto.</li>
        <li><strong>Reprogramación</strong> de la ECU con WinOLS, a medida de tu motor y del Stage elegido.</li>
        <li><strong>Medición final</strong> en el banco, para comprobar la ganancia real.</li>
      </ol>
      <a href="{{ route('chipMarcas') }}" class="btn btn-primary btn-sm">Buscá tu vehículo</a>
    </section>

    <section class="pres-seccion">
      <h2>El taller</h2>
      <p class="inicio-galeria-intro">Banco de rodillos para probar el vehículo a alta velocidad sin salir del taller, laboratorio electrónico propio y sala de espera para que esperes tu trabajo o presupuesto con comodidad.</p>
      <div class="inicio-galeria">
        @foreach ([
          ['taller-frente', 'Nuestro local en Morón'],
          ['taller-nave', 'La nave del taller'],
          ['taller-banco', 'Banco de rodillos'],
          ['taller-laboratorio', 'Laboratorio electrónico'],
          ['taller-recepcion', 'Recepción y sala de espera'],
          ['taller-mecanica', 'Trabajos de mecánica'],
        ] as [$foto, $epigrafe])
        <figure>
          <a href="{{ asset('img/inicio/' . $foto . '.webp') }}" target="_blank" rel="noopener">
            <img src="{{ asset('img/inicio/' . $foto . '.webp') }}" alt="{{ $epigrafe }}" loading="lazy" width="1000" height="750">
          </a>
          <figcaption>{{ $epigrafe }}</figcaption>
        </figure>
        @endforeach
      </div>
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
          @include('pages.partials.novedad-media', ['novedad' => $novedad, 'origen' => ['1' => 'Facebook', '2' => 'Instagram', '3' => 'Twitter', '4' => 'YouTube'][(string) $novedad->tipo_novedad] ?? 'Web'])
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
