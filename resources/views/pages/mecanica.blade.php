@extends('layouts.app', ['page' => 'Mecánica', 'pageSlug' => 'mecanica'])

@section('content')
<div class="card">
  <div class="card-body">

    <x-sitio.hero titulo="Mecánica integral"
      texto="Soria Perfomance es una pyme familiar dedicada a la reparación y el mantenimiento del automóvil desde hace más de 27 años en la Zona Oeste. Incorporamos la mejor tecnología y equipamiento para darte un servicio de calidad y más rapidez en cada reparación."
      whatsapp="Hola! Quisiera un presupuesto de mecánica">
      <a href="{{ route('mantenimiento') }}" class="btn btn-secondary">Mantenimiento programado</a>
    </x-sitio.hero>

    <div class="pres-galeria">
      <img loading="lazy" src="{{ asset('black/img/Mecanica/mecanica1.jpg') }}" alt="Trabajo de mecánica en el taller">
      <img loading="lazy" src="{{ asset('black/img/Mecanica/mecanica2.jpg') }}" alt="Reparación de motor">
      <img loading="lazy" src="{{ asset('black/img/Mecanica/mecanica3.jpg') }}" alt="Tren delantero">
      <img loading="lazy" src="{{ asset('black/img/Mecanica/mecanica4.jpg') }}" alt="Taller Soria Perfomance">
    </div>

    <section class="pres-seccion">
      <h2>Qué hacemos</h2>
      <div class="pres-tarjetas">
        <div class="pres-tarjeta">
          <h3>Motor</h3>
          <p>Reparación de motores y tapas de cilindros. Cambio de correas de distribución y accesorios.</p>
        </div>
        <div class="pres-tarjeta">
          <h3>Frenos</h3>
          <p>Frenos convencionales y ABS: diagnóstico, reparación y codificación.</p>
        </div>
        <div class="pres-tarjeta">
          <h3>Tren delantero y suspensión</h3>
          <p>Tren delantero, tren trasero y amortiguación.</p>
        </div>
        <div class="pres-tarjeta">
          <h3>Electricidad y electrónica</h3>
          <p>Electricidad del automotor, codificación de llaves y programación de módulos: PCM, ABS, airbag y BSI.</p>
        </div>
      </div>
      <p class="mt-3">Trabajamos en vehículos nafta y diésel.</p>
    </section>

    <section class="pres-seccion">
      <h2>Nuestro taller</h2>
      <ul class="pres-lista pres-lista-check">
        <li>Personal capacitado en todas las áreas del automóvil</li>
        <li>Sistema de gestión con el historial de cada vehículo y los trabajos realizados</li>
        <li>Banco de rodillos para pruebas a alta velocidad: detectamos fallas sin salir del taller</li>
        <li>Sala de espera cómoda mientras esperás tu trabajo o presupuesto</li>
      </ul>
    </section>

    <section class="pres-seccion">
      <h2>Flotas y empresas</h2>
      <p>Ofrecemos servicios a flotas y empresas. Escribinos a <a href="mailto:info@aldosoria.com.ar">info@aldosoria.com.ar</a>. Estas son algunas de las empresas que confían en nosotros:</p>
      <ul class="pres-chips">
        @foreach (['AllBroker S.A.', 'Claralux', 'Profertil', 'YPF', 'Talleres Gráficos Papelco', 'Farmesa Laboratorio', 'Poder Judicial Morón (Ministerio Público)', 'Serenity S.A.', 'Club San Jorge S.A.', 'Ing Plast S.R.L.', 'Servitec Comunicaciones S.R.L.', 'Laboratorio Ferring S.A.', 'Schering Plough S.A.', 'Emelec S.A.', 'Finca del Manantial S.A.', 'EG3 Red S.A.', 'Calico S.R.L.'] as $cliente)
        <li>{{ $cliente }}</li>
        @endforeach
      </ul>
    </section>

    <section class="pres-seccion">
      <h2>Quiénes somos</h2>
      <div class="pres-tarjetas">
        <div class="pres-tarjeta">
          <h3>Visión</h3>
          <p>Dedicación a la mecánica y la electrónica del vehículo. Ser reconocidos en el país y en el exterior por la experiencia que nos avala y por el cuidado en cada reparación.</p>
        </div>
        <div class="pres-tarjeta">
          <h3>Misión</h3>
          <p>Lograr y mantener la mayor calidad en nuestros servicios, atendiendo las necesidades de cada cliente con el mayor compromiso, y seguir innovando en técnicas de mecánica y electrónica automotor.</p>
        </div>
        <div class="pres-tarjeta">
          <h3>Trayectoria</h3>
          <p>“Soria Perfomance” encierra 47 años de dedicación, calidad e innovación. Dictamos cursos de inyección electrónica, y nuestro fundador Aldo Soria fue premiado en varios seminarios de gestión electrónica automotor.</p>
        </div>
      </div>
    </section>

    <x-sitio.contacto mensaje="Hola! Quisiera un presupuesto de mecánica" />
  </div>
</div>
@endsection
