<div class="sidebar">
  <div class="sidebar-wrapper">
    <div class="logo">
      <a href="{{ route('home') }}" class="simple-text logo-mini">
        {{-- <i class="tim-icons icon-bank"></i> --}}
        {{ __('AS') }} </a>
      <a href="{{ route('home') }}" class="simple-text logo-normal">{{ __('Perfomance') }}</a>
    </div>
    <ul class="nav">
      @if (Auth::user())
        <li @if ($pageSlug == 'dashboard') class="active " @endif>
          <a href="{{ route('dashboard') }}">
            <i class="tim-icons icon-chart-pie-36"></i>
            <p>{{ __('Dashboard') }}</p>
          </a>
        </li>
      @endif
      <li @if ($pageSlug == 'mecanica') class="active " @endif>
        <a href="{{ route('mecanica') }}">
          <i class="tim-icons icon-settings"></i>
          <p>{{ __('Mecánica') }}</p>
        </a>
      </li>

      <li @if ($pageSlug == 'electronica') class="active " @endif>
        <a href="{{ route('electronica') }}">
          <i class="tim-icons icon-sound-wave"></i>
          <p>{{ __('Electrónica') }}</p>
        </a>
      </li>

      <li @if ($pageSlug == 'chiptuning') class="active " @endif>
        <a href="{{ route('chiptuning') }}">
          <i class="tim-icons icon-spaceship"></i>
          <p>{{ __('Chiptuning ') }}</p>
        </a>
      </li>

      <li @if ($pageSlug == 'representacion') class="active " @endif>
        <a href="{{ route('representacion') }}">
          <i class="tim-icons icon-align-center"></i>
          <p>{{ __('Representación Oficial') }}</p>
        </a>
      </li>

      <li @if ($pageSlug == 'novedades') class="active " @endif>
        <a href="{{ route('novedad') }}">
          <i class="tim-icons icon-single-copy-04"></i>
          <p>{{ __('Novedades') }}</p>
        </a>
      </li>

      <li @if ($pageSlug == 'cursos') class="active " @endif>
        <a href="{{ route('cursos') }}">
          <i class="tim-icons icon-video-66"></i>
          <p>{{ __('Cursos') }}</p>
        </a>
      </li>

      <li @if ($pageSlug == 'contacto') class="active " @endif>
        <a href="{{ route('contacto') }}">
          <i class="tim-icons icon-world"></i>
          <p>{{ __('Contacto') }}</p>
        </a>
      </li>
    </ul>
    <div class="logo"></div>
    <div class="mt-2 justify-content-center text-center">
      <h4>Oferta</h4>
      <img src="{{ asset('img/Ofertas/oferta.jpg') }}?v={{ @filemtime(public_path('img/Ofertas/oferta.jpg')) }}" width="60%" alt="Oferta" loading="lazy">
    </div>
  </div>
</div>
