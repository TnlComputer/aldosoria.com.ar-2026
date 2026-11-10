@extends('layouts.app', [
  'page' => 'Representante oficial NAPRO',
  'pageSlug' => 'representacion',
  'description' => 'Soria Perfomance es representante oficial del escáner de diagnóstico NAPRO de Brasil. Venta de equipos y soporte en Argentina.',
])

@section('content')
<div class="card">
  <div class="card-body">

    <x-sitio.hero titulo="Representación oficial"
      texto="Somos los únicos autorizados en el país para vender los equipos de diagnóstico NAPRO Electronic de Brasil."
      whatsapp="Hola! Quiero información sobre NAPRO">
      <a href="#napro" class="btn btn-primary">NAPRO · Diagnóstico</a>
    </x-sitio.hero>

    {{-- NAPRO --}}
    <section class="pres-seccion" id="napro">
      <div class="pres-dos">
        <img src="{{ asset('black/img/telaspcscan3000.png') }}" alt="Pantallas del escáner NAPRO PC-SCAN 3000" loading="lazy">
        <div>
          <img src="{{ asset('black/img/napro.jpg') }}" alt="NAPRO Electronic" loading="lazy" style="max-width:160px;border-radius:8px;margin-bottom:12px">
          <h2>NAPRO PC-SCAN 3000 USB</h2>
          <p>Escáner de diagnóstico con tecnología brasileña que cubre la mayor parte del parque automotor nacional e importado. Funciona conectado a una PC o notebook por USB, con respuesta rápida y bajo costo de mantenimiento.</p>
          <p>Ideal para iniciarse en la inyección electrónica por costo y prestaciones, con algunas funciones de programación.</p>
          <a href="http://www.napro.com.br" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-secondary">Sitio de NAPRO</a>
          <a href="https://www.suporte.napro.com.br/index.php?language=Espa%C3%B1ol" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-secondary">Soporte NAPRO</a>
        </div>
      </div>
    </section>

    <section class="pres-seccion">
      <h2>Características</h2>
      <div class="pres-tarjetas">
        <div class="pres-tarjeta">
          <h3>Diagnóstico completo</h3>
          <ul>
            <li>Lee y borra códigos de falla</li>
            <li>Prueba de sensores y actuadores</li>
            <li>Ajustes básicos cuando hacen falta</li>
            <li>Lectura en vivo de varios parámetros a la vez</li>
            <li>Escáner VAG (VW, Audi, Seat, Skoda) con el procedimiento de fábrica</li>
          </ul>
        </div>
        <div class="pres-tarjeta">
          <h3>Fácil de usar</h3>
          <p>No hace falta saber de computación: el programa guía paso a paso e indica qué cable o conector usar.</p>
        </div>
        <div class="pres-tarjeta">
          <h3>Software liberado</h3>
          <p>Siempre completo y desbloqueado: no hay que comprar paquetes por marca. Cada actualización se compra sin necesidad de las anteriores.</p>
        </div>
        <div class="pres-tarjeta">
          <h3>Conectores</h3>
          <p>El kit básico trae OBD2 / ISO. Opcionales: ALDL10, ALDL12, adaptador ISO, PSA, EC4 / DLC, SW / SAE-M / CAN (3 en 1), Honda, Fiat, MB38 y más.</p>
        </div>
      </div>
    </section>

    <section class="pres-seccion">
      <h2>Marcas compatibles</h2>
      <ul class="pres-chips">
        @foreach (['Agrale', 'Alfa Romeo', 'Audi', 'BMW', 'Chamonix', 'Chery', 'Chrysler', 'Citroën', 'CN Auto', 'Dacia', 'Daewoo', 'Dodge', 'Effa', 'Fiat', 'Ford', 'GM / Chevrolet', 'Hafei', 'Holden', 'Honda', 'Hyundai', 'Iveco', 'JAC', 'Jaguar', 'Jeep', 'Kia', 'Lancia', 'Land Rover', 'Lexus', 'Lifan', 'Mazda', 'Mercedes-Benz', 'Mini', 'Mitsubishi', 'Honda Motos', 'Nissan', 'Opel', 'Peugeot', 'Renault', 'Seat', 'Shineray', 'Skoda', 'SsangYong', 'Suzuki', 'Toyota', 'Troller', 'Volvo', 'Volkswagen', 'Yamaha'] as $marca)
        <li>{{ $marca }}</li>
        @endforeach
      </ul>
    </section>

    @include('pages.partials.formas-pago')

    <x-sitio.contacto titulo="¿Querés equipar tu taller?" mensaje="Hola! Quiero información sobre NAPRO" />
  </div>
</div>
@endsection
