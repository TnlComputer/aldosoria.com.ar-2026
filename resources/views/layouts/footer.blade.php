<footer class="footer pie">
  {{-- Llamada a la accion --}}
  <div class="pie-cta">
    <div>
      <p class="pie-cta-titulo">¿Tu auto puede rendir más?</p>
      <p class="pie-cta-texto">Pedí tu presupuesto de chiptuning, mecánica o electrónica. Te respondemos a la brevedad.</p>
    </div>
    <div class="pie-cta-botones">
      <a href="https://wa.me/{{ config('services.whatsapp') }}?text={{ rawurlencode('Hola! Quisiera pedir un presupuesto') }}" class="btn chip-whatsapp" target="_blank" rel="noopener noreferrer">
        <img src="{{ asset('black/img/social/whatsapp.svg') }}" alt="" width="20" height="20"> WhatsApp
      </a>
      <a href="{{ route('contacto') }}" class="btn pie-cta-secundario">Escribinos</a>
    </div>
  </div>

  <div class="pie-grilla">
    <div class="pie-marca">
      <a href="{{ route('home') }}" aria-label="Soria Perfomance, inicio">
        <img src="{{ asset('img/inicio/logo-soria.webp') }}" alt="Soria Perfomance" width="200" height="59" loading="lazy">
      </a>
      <p>Taller familiar en Morón con más de 27 años de experiencia. Banco de potencia propio, laboratorio electrónico y cursos online de reprogramación.</p>
      <x-sitio.redes class="pie-redes" />
    </div>

    <nav class="pie-columna" aria-label="Servicios">
      <h2>Servicios</h2>
      <a href="{{ route('chiptuning') }}">Chiptuning</a>
      <a href="{{ route('mecanica') }}">Mecánica</a>
      <a href="{{ route('electronica') }}">Electrónica</a>
      <a href="{{ route('mantenimiento') }}">Mantenimiento programado</a>
      <a href="{{ route('cursos') }}">Cursos online</a>
    </nav>

    <div class="pie-columna pie-contacto">
      <h2>Visitanos</h2>
      <a href="https://www.google.com/maps/dir/?api=1&destination={{ rawurlencode('Soria Perfomance, General A. J. de Sucre 1053, Morón, Buenos Aires') }}" target="_blank" rel="noopener noreferrer">
        <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
        <span>General A. J. de Sucre 1053/75<br><small>CP 1708 · Morón, Buenos Aires</small></span>
      </a>
      <a href="tel:+541144831235"><i class="fas fa-phone" aria-hidden="true"></i> <span>4483-1235 · 4629-8829 · 5294-4412</span></a>
      <a href="mailto:info@aldosoria.com.ar"><i class="fas fa-envelope" aria-hidden="true"></i> <span>info@aldosoria.com.ar</span></a>
      <a href="https://t.me/Soriaperfomance" target="_blank" rel="noopener noreferrer"><i class="fab fa-telegram-plane" aria-hidden="true"></i> <span>Telegram</span></a>
    </div>
  </div>

  <div class="pie-base">
    <span>&copy; {{ now()->year }} Soria Perfomance · <a href="https://www.tnlcomputer.com.ar" target="_blank" rel="noopener">Sitio por Tnlcomputer Design</a></span>
    <a class="pie-afip" href="http://qr.afip.gob.ar/?qr=MG5a_WCRRvyhXRiEw1fP5Q,," target="_F960AFIPInfo" rel="noopener">
      <img src="https://www.afip.gob.ar/images/f960/DATAWEB.jpg" alt="Data fiscal AFIP" width="36" height="49" loading="lazy">
    </a>
  </div>
</footer>
