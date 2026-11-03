@props(['href', 'titulo' => 'Editar', 'icono' => 'icon-pencil'])

<a href="{{ $href }}" class="admin-icono" title="{{ $titulo }}" aria-label="{{ $titulo }}">
  <i class="tim-icons {{ $icono }}"></i>
</a>
