@extends('layouts.app', [
  'page' => 'Mantenimiento programado',
  'pageSlug' => 'mantenimiento',
  'description' => 'Mantenimiento programado de tu auto en Morón: te avisamos por email cuándo cambiar aceite y filtros y revisamos los 11 puntos clave del vehículo.',
])

@section('content')
<div class="card">
  <div class="card-body">

    <x-sitio.hero titulo="Mantenimiento programado"
      texto="¿Sabés cuántos kilómetros tiene tu aceite? Nosotros te ayudamos a no olvidarte: te avisamos por email cuándo cambiar filtros y lubricantes para que tu auto esté siempre en condiciones."
      whatsapp="Hola! Quiero pedir un turno para el service">
    </x-sitio.hero>

    <div class="pres-dos mt-3">
      <img loading="lazy" src="{{ asset('black/img/mantenimiento-programado.jpg') }}" alt="Mantenimiento programado">
      <div>
        <h2 class="text-white" style="font-size:1.35rem">Cómo funciona</h2>
        <ul class="pres-lista">
          <li>Traés tu auto al service</li>
          <li>Revisamos los 11 puntos clave</li>
          <li>Te avisamos por email cuando toca el próximo</li>
        </ul>
      </div>
    </div>

    <section class="pres-seccion">
      <h2>Los 11 puntos que controlamos en cada service</h2>
      <ol class="pres-lista">
        <li>Elevación del vehículo para revisar la parte de abajo</li>
        <li>Cambio de lubricantes de motor, caja de velocidades y caja de transferencia en 4x4</li>
        <li>Cambio de filtros de aceite, aire, combustible y habitáculo</li>
        <li>Control de frenos convencionales y ABS</li>
        <li>Control del refrigerante del motor</li>
        <li>Control de calidad y degradación del líquido de frenos</li>
        <li>Control de tren delantero y trasero</li>
        <li>Control de fuelles de semiejes y sus juntas</li>
        <li>Control de amortiguadores</li>
        <li>Control electrónico de todos los sistemas</li>
        <li>Control de luces exteriores y faros auxiliares</li>
      </ol>
    </section>

    <section class="pres-seccion">
      <p class="pres-nota mb-0">♻️ El taller recolecta los residuos peligrosos y los entrega a empresas especializadas para su tratamiento, cuidando el medio ambiente.</p>
    </section>

    <x-sitio.contacto titulo="¿Querés un turno para el service?" mensaje="Hola! Quiero pedir un turno para el service" />
  </div>
</div>
@endsection
