<div class="sidebar">
  <div class="sidebar-wrapper">
    <div class="logo">
      {{-- <a href="{{ route('welcome') }}" class="simple-text logo-mini">{{ __('AS') }}</a>
      <a href="{{ route('welcome') }}" class="simple-text logo-normal">{{ __('Perfomance') }}</a> --}}
    </div>
    <ul class="nav">

      <li @if ($pageSlug=='welcome' ) class="active " @endif>
        <a href="{{ route('welcome') }}">
          <i class="tim-icons icon-settings"></i>
          <p>{{ __('Welcome') }}</p>
        </a>
      </li>

      <li @if ($pageSlug=='mecanica' ) class="active " @endif>
        <a href="{{ route('mecanica') }}">
          <i class="tim-icons icon-settings"></i>
          <p>{{ __('Mecánica') }}</p>
        </a>
      </li>

      <li @if ($pageSlug=='electronica' ) class="active " @endif>
        <a href="{{ route('electronica') }}">
          <i class="tim-icons icon-sound-wave"></i>
          <p>{{ __('Electrónica') }}</p>
        </a>
      </li>

      <li @if ($pageSlug=='chiptuning' ) class="active " @endif>
        <a href="{{ route('chiptuning') }}">
          <i class="tim-icons icon-spaceship"></i>
          <p>{{ __('Chiptuning ') }}</p>
        </a>
      </li>

      <li @if ($pageSlug=='representacion' ) class="active " @endif>
        <a href="{{ route('representacion') }}">
          <i class="tim-icons icon-align-center"></i>
          <p>{{ __('Representación Oficial') }}</p>
        </a>
      </li>

      <li @if ($pageSlug=='novedades' ) class="active " @endif>
        <a href="{{ route('novedades') }}">
          <i class="tim-icons icon-single-copy-04"></i>
          <p>{{ __('Novedades') }}</p>
        </a>
      </li>

      <li @if ($pageSlug=='cursos' ) class="active " @endif>
        <a href="{{ route('cursos') }}">
          <i class="tim-icons icon-video-66"></i>
          <p>{{ __('Cursos') }}</p>
        </a>
      </li>

      {{-- <li @if ($pageSlug=='curso' ) class="active " @endif>
        <a href="{{ route('pages.curso') }}">
      <i class="tim-icons icon-video-66"></i>
      <p>{{ __('Curso') }}</p>
      </a>
      </li> --}}

      <li @if ($pageSlug=='contacto' ) class="active " @endif>
        <a href="{{ route('contacto') }}">
          <i class="tim-icons icon-world"></i>
          <p>{{ __('Contacto') }}</p>
        </a>
      </li>
    </ul>
  </div>
</div>
