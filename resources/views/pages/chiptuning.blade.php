@extends('layouts.app', ['page' => 'Chiptuning', 'pageSlug' => 'chiptuning'])

@section('content')
<div class="card">
  <div class="card-body">

    <div class="pres-hero">
      <h1>Chiptuning y reprogramación de ECU</h1>
      <p>Más potencia, más torque y mejor consumo, medidos antes y después en nuestro banco de rodillos. Nafta y diésel, aspirados y turbo, y cajas automáticas DSG.</p>
      <a href="{{ route('chipMarcas') }}" class="btn btn-primary"><i class="tim-icons icon-zoom-split"></i> Buscá tu vehículo</a>
      <a href="https://wa.me/{{ config('services.whatsapp') }}?text={{ rawurlencode('Hola! Quiero consultar por chiptuning') }}" class="btn chip-whatsapp" target="_blank" rel="noopener noreferrer">
        <img src="{{ asset('black/img/social/whatsapp.svg') }}" alt="" width="20" height="20"> Consultar por WhatsApp
      </a>

      <div class="pres-datos">
        <div class="pres-dato"><strong>{{ $fichas }}</strong><span>vehículos con datos de banco</span></div>
        <div class="pres-dato"><strong>{{ $marcas->count() }}</strong><span>marcas reprogramadas</span></div>
        <div class="pres-dato"><strong>300 km/h</strong><span>de prueba en el banco de rodillos</span></div>
        <div class="pres-dato"><strong>22 años</strong><span>de experiencia en electrónica automotor</span></div>
      </div>
    </div>

    <div class="pres-galeria">
      <img loading="lazy" src="{{ asset('black/img/BancoPrueba/banco_prueba.jpg') }}" alt="Banco de rodillos del taller">
      <img loading="lazy" src="{{ asset('black/img/Chiptuning/evc_lab.jpg') }}" alt="Reprogramación de ECU con WinOLS">
      <img loading="lazy" src="{{ asset('black/img/Chiptuning/2014071.jpg') }}" alt="Lectura de ECU en banco de trabajo">
      <img loading="lazy" src="{{ asset('black/img/Chiptuning/2014072.jpg') }}" alt="Unidad de mando abierta para lectura">
    </div>

    <section class="pres-seccion">
      <h2>Marcas que reprogramamos</h2>
      <div class="chip">
        <div class="chip-marcas">
          @foreach ($marcas as $m)
          <a href="{{ route('chipMarcas', ['marca' => $m['nombre']]) }}" class="chip-marca" style="text-decoration:none">
            @if ($m['logo'])
            <img src="{{ $m['logo'] }}" alt="" loading="lazy">
            @endif
            <span class="chip-marca-nombre">{{ $m['nombre'] }}</span>
            <span class="chip-marca-total">{{ $m['total'] }} {{ $m['total'] == 1 ? 'ficha' : 'fichas' }}</span>
          </a>
          @endforeach
        </div>
      </div>
      <p class="chip-nota mt-2">¿No está tu marca? Consultanos igual: trabajamos con la mayoría de las unidades de mando del mercado.</p>
    </section>

    <section class="pres-seccion">
      <h2>¿Qué es un chip?</h2>
      <p>Es una memoria donde se guardan los mapas del motor: avance de encendido, cantidad de combustible, cargas de motor, limitadores de RPM y de velocidad máxima, y la carga del turbo. Ajustando estos factores se logran <strong>más potencia, más torque y mejores consumos</strong>.</p>
      <p>Los sistemas más antiguos tenían una memoria que se desmontaba de la unidad de mando para modificarla. Los modernos guardan la información en el microcontrolador, y hacen falta equipos de muy buena calidad para leerlos y grabarlos.</p>
    </section>

    <section class="pres-seccion">
      <h2>Tipos de lectura</h2>
      <div class="pres-tarjetas">
        <div class="pres-tarjeta">
          <h3>OBD</h3>
          <p>Por el conector de diagnóstico, sin desmontar nada.</p>
        </div>
        <div class="pres-tarjeta">
          <h3>Boot Mode</h3>
          <p>Desmontando la ECU de motor para leerla en el banco de trabajo.</p>
        </div>
        <div class="pres-tarjeta">
          <h3>BDM</h3>
          <p>Por el puerto BDM que tienen algunos modelos, siempre con la ECU desmontada.</p>
        </div>
        <div class="pres-tarjeta">
          <h3>Cajas automáticas</h3>
          <p>Programación de cajas DSG DQ200, DQ250, DQ300 y DQ500 del grupo VAG y BMW.</p>
        </div>
      </div>
    </section>

    @include('pages.partials.chip-stages')

    <section class="pres-seccion">
      <h2>Por qué con nosotros</h2>
      <p>Los vehículos que llegan al país vienen con normas de emisiones europeas (Euro 3, Euro 4 y posteriores) y muchas veces les falta potencia con nuestros combustibles. Por eso incorporamos tecnología y pruebas con equipos de alta precisión.</p>
      <p><strong>Tenemos banco de rodillos</strong> para medir la potencia antes y después de cada modificación, y probar el vehículo hasta 300 km/h sin salir del taller. <strong>Somos especialistas en inyección electrónica nafta y diésel, aspirados y turbo, y en cajas automáticas DSG del grupo VAG</strong>, con 22 años de experiencia en electrónica automotor. También hacemos Stages a medida con agua-metanol.</p>
      <a href="https://www.evc.de/en/check_evc_license.asp?k=kAV25xWfgnfRmeLXxsUPHQ%3d%3d" target="_blank" rel="noopener noreferrer">
        <img src="{{ asset('black/img/check_evc_license_image.png') }}" width="300" height="75" alt="Licencia WinOLS verificada" title="Verificar licencia WinOLS" loading="lazy" style="max-width:100%;height:auto">
      </a>
    </section>

    <section class="pres-seccion">
      <h2>Formas de pago</h2>
      <div class="pres-pagos">
        <img loading="lazy" src="{{ asset('black/img/mercadoPago1.png') }}" alt="MercadoPago">
        <img loading="lazy" src="{{ asset('black/img/visa.jpg') }}" alt="Visa">
        <img loading="lazy" src="{{ asset('black/img/mastercard.jpg') }}" alt="Mastercard">
        <img loading="lazy" src="{{ asset('black/img/american_express.jpg') }}" alt="American Express">
        <img loading="lazy" src="{{ asset('black/img/argencard.jpg') }}" alt="Argencard">
        <img loading="lazy" src="{{ asset('black/img/cabal.jpg') }}" alt="Cabal">
        <img loading="lazy" src="{{ asset('black/img/tarjeta_naranja.jpg') }}" alt="Tarjeta Naranja">
        <img loading="lazy" src="{{ asset('black/img/paypal.gif') }}" alt="PayPal">
      </div>
    </section>
  </div>
</div>
@endsection
