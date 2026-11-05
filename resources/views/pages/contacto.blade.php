@extends('layouts.app', [
  'page' => 'Contacto',
  'pageSlug' => 'contacto',
  'description' => 'Contactá a Soria Perfomance: presupuestos de chiptuning, turnos para el taller y consultas sobre cursos. General A. J. de Sucre 1053/75, Morón, Buenos Aires.',
])

@php
  // El asunto puede venir precargado desde otras paginas (?asunto=...)
  $asunto = \Illuminate\Support\Str::limit(trim(strip_tags((string) request('asunto'))), 150, '');
  $temas = ['Presupuesto de chiptuning', 'Turno para el taller', 'Consulta sobre cursos', 'Electrónica y diagnóstico'];
  $usuario = auth()->user();
  $mapa = 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode('Soria Perfomance, General A. J. de Sucre 1053, Morón, Buenos Aires');
@endphp

@section('content')
<div class="card">
  <div class="card-body">

    <x-sitio.hero titulo="Contacto"
      texto="Contanos qué necesitás y te respondemos a la brevedad. Para algo urgente, lo más rápido es WhatsApp.">
      <div class="contacto-rapido">
        <a href="https://wa.me/{{ config('services.whatsapp') }}?text={{ rawurlencode('Hola! Quisiera más información') }}" class="btn chip-whatsapp" target="_blank" rel="noopener noreferrer">
          <img src="{{ asset('black/img/social/whatsapp.svg') }}" alt="" width="20" height="20"> WhatsApp
        </a>
        <a href="tel:+541144831235" class="btn btn-secondary"><i class="fas fa-phone" aria-hidden="true"></i> Llamar</a>
        <a href="{{ $mapa }}" class="btn btn-secondary" target="_blank" rel="noopener noreferrer"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Cómo llegar</a>
      </div>
    </x-sitio.hero>

    <div class="contacto-grilla pres-seccion">
      <form class="pres-tarjeta admin-form" action="{{ route('contacto.enviar') }}" method="post" id="formulario">
        @csrf
        <h2 class="contacto-titulo">Envianos un mensaje</h2>
        <x-admin.alertas />
        <x-antibot />

        <p class="contacto-temas-titulo">¿Sobre qué es tu consulta?</p>
        <div class="contacto-temas">
          @foreach ($temas as $tema)
          <a href="{{ route('contacto', ['asunto' => $tema]) }}#formulario" @class(['contacto-tema', 'activo' => old('txtAsunto', $asunto) === $tema])>{{ $tema }}</a>
          @endforeach
        </div>

        <div class="form-row">
          <x-admin.campo name="txtNombre" label="Nombre" required maxlength="100" autocomplete="name" :value="$usuario ? trim($usuario->name . ' ' . $usuario->last_name) : null" />
          <x-admin.campo name="txtTelefono" label="Teléfono" type="tel" required maxlength="30" autocomplete="tel" :value="$usuario?->phone" />
          <x-admin.campo name="txtEmail" label="Email" type="email" required maxlength="150" autocomplete="email" col="col-md-12" :value="$usuario?->email" />
          <x-admin.campo name="txtAsunto" label="Asunto" required maxlength="150" col="col-md-12" placeholder="Ej.: Presupuesto Stage 1 Golf GTI" :value="$asunto ?: null" />
          <x-admin.campo name="txtMensaje" label="Mensaje" type="textarea" required maxlength="5000" col="col-md-12" rows="6"
            placeholder="Si es por tu auto, contanos marca, modelo, año y motor." />
        </div>

        <button type="submit" class="btn btn-primary btn-block mt-2">Enviar mensaje</button>
        <p class="contacto-privacidad">Usamos tus datos solo para responderte.</p>
      </form>

      <div class="contacto-datos">
        <div class="pres-tarjeta mb-3">
          <h3 class="mb-3">Soria Perfomance</h3>
          <div class="contacto-dato">
            <i class="tim-icons icon-pin"></i>
            <div>
              <a href="{{ $mapa }}" target="_blank" rel="noopener noreferrer">General A. J. de Sucre 1053/75</a>
              <small>CP 1708 · Morón, Provincia de Buenos Aires</small>
            </div>
          </div>
          <div class="contacto-dato">
            <i class="tim-icons icon-mobile"></i>
            <div>
              <a href="tel:+541144831235">4483-1235</a> · <a href="tel:+541146298829">4629-8829</a> · <a href="tel:+541152944412">5294-4412</a>
              <small>Teléfonos del taller</small>
            </div>
          </div>
          <div class="contacto-dato">
            <i class="tim-icons icon-email-85"></i>
            <div>
              <a href="mailto:info@aldosoria.com.ar">info@aldosoria.com.ar</a>
              <small>Flotas, empresas y presupuestos</small>
            </div>
          </div>
          <p class="contacto-temas-titulo mt-3">Seguinos</p>
          <x-sitio.redes />
        </div>

        <iframe class="contacto-mapa" loading="lazy" title="Mapa de Soria Perfomance" referrerpolicy="no-referrer-when-downgrade"
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3281.7933289046136!2d-58.63106368431864!3d-34.65992198044491!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bcc7449c36ec85%3A0x7653129a31a6ad8b!2sSoria+Perfomance!5e0!3m2!1ses-419!2sus!4v1554908259641!5m2!1ses-419!2sus"></iframe>
      </div>
    </div>
  </div>
</div>
@endsection
