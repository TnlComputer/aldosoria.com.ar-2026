@props(['valor' => '', 'placeholder' => 'Buscar...', 'filtrando' => null])

@php($hayFiltro = $filtrando ?? $valor !== '')

<form method="get" action="{{ url()->current() }}" class="admin-buscador" role="search">
  <div class="admin-buscador-campo">
    <i class="tim-icons icon-zoom-split"></i>
    <input type="search" name="q" value="{{ $valor }}" maxlength="100" class="form-control" placeholder="{{ $placeholder }}" aria-label="{{ $placeholder }}">
  </div>
  {{ $slot }}
  <button type="submit" class="btn btn-sm btn-primary">Buscar</button>
  @if ($hayFiltro)
  <a href="{{ url()->current() }}" class="btn btn-sm btn-link">Limpiar</a>
  @endif
</form>
