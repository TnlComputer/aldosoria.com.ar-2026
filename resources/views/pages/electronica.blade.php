@extends('layouts.app', ['page' => 'Electrónica', 'pageSlug' => 'electronica'])

@section('content')
<div class="card">
  <div class="card-body">

    <x-sitio.hero titulo="Electrónica automotor"
      texto="Años de trabajo nos dieron muchísima información que ponemos a tu disposición para resolver los problemas de tu auto. Tenemos laboratorio electrónico propio para reparar módulos que se pueden recuperar."
      whatsapp="Hola! Tengo un problema electrónico en mi auto" />

    <div class="pres-dos mt-3">
      <img loading="lazy" src="{{ asset('black/img/Electronica/13607046.jpg') }}" alt="Laboratorio electrónico">
      <img loading="lazy" src="{{ asset('black/img/Electronica/13659120.jpg') }}" alt="Reparación de módulos electrónicos">
    </div>

    <section class="pres-seccion">
      <h2>Qué resolvemos</h2>
      <div class="pres-tarjetas">
        <div class="pres-tarjeta">
          <h3>Inyección nafta y diésel</h3>
          <p>Diagnóstico y reparación con equipamiento profesional en nuestro laboratorio.</p>
        </div>
        <div class="pres-tarjeta">
          <h3>Frenos ABS</h3>
          <p>Controlamos el módulo con nuestros equipos y evaluamos si se puede reparar y codificar o hay que reemplazarlo.</p>
        </div>
        <div class="pres-tarjeta">
          <h3>Airbag</h3>
          <p>Cuando se activa, el calculador guarda los datos de la colisión. Lo dejamos nuevamente operativo y codificado cuando es posible.</p>
        </div>
        <div class="pres-tarjeta">
          <h3>Lectura de pin code</h3>
          <p>Con los cambios de dueño el auto puede perder su pin code, necesario para programar módulos, llaves y variantes. Lo recuperamos.</p>
        </div>
        <div class="pres-tarjeta">
          <h3>Codificación de llaves</h3>
          <p>Llaves y telemandos originales con su codificación.</p>
        </div>
        <div class="pres-tarjeta">
          <h3>Inmovilizador</h3>
          <p>Cuando no hay forma de recuperar las llaves codificadas, podemos anular el sistema para que tu vehículo siga funcionando.</p>
        </div>
      </div>
    </section>

    <section class="pres-seccion">
      <p class="pres-nota mb-0">♻️ Guardamos los desechos electrónicos y los entregamos a empresas especializadas para su tratamiento y reutilización, para que no contaminen el suelo.</p>
    </section>

    @include('pages.partials.formas-pago')

    <x-sitio.contacto mensaje="Hola! Tengo un problema electrónico en mi auto" />
  </div>
</div>
@endsection
