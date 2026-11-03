@props(['texto' => 'No hay registros para mostrar.'])

<div class="admin-vacio">
  <i class="tim-icons icon-bullet-list-67"></i>
  <p>{{ $texto }}</p>
  {{ $slot }}
</div>
