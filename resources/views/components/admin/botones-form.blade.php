@props(['cancelar', 'texto' => 'Guardar'])

<div class="admin-botones-form">
  <a href="{{ $cancelar }}" class="btn btn-link admin-cancelar">Cancelar</a>
  <button type="submit" class="btn btn-primary admin-guardar">
    <i class="tim-icons icon-check-2"></i> {{ $texto }}
  </button>
</div>
