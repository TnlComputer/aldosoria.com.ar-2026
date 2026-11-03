@props(['mensaje' => 'Hola! Quisiera más información', 'titulo' => '¿Necesitás un presupuesto o un turno?'])

<section class="pres-seccion pres-contacto">
  <div>
    <h2>{{ $titulo }}</h2>
    <p class="mb-0">Escribinos y te respondemos a la brevedad. Estamos en General A. J. de Sucre 1053, Morón, Buenos Aires.</p>
  </div>
  <div class="pres-contacto-botones">
    <a href="https://wa.me/{{ config('services.whatsapp') }}?text={{ rawurlencode($mensaje) }}" class="btn chip-whatsapp" target="_blank" rel="noopener noreferrer">
      <img src="{{ asset('black/img/social/whatsapp.svg') }}" alt="" width="20" height="20"> WhatsApp
    </a>
    <a href="{{ route('contacto') }}" class="btn btn-secondary">Formulario de contacto</a>
  </div>
</section>
