@props(['action', 'nombre' => '', 'que' => 'el registro'])

<form action="{{ $action }}" method="post" class="formEliminar d-inline" data-nombre="{{ $nombre }}" data-que="{{ $que }}">
  @csrf
  @method('DELETE')
  <button type="submit" class="admin-icono admin-icono-borrar" title="Eliminar {{ $nombre }}" aria-label="Eliminar {{ $nombre }}">
    <i class="tim-icons icon-trash-simple"></i>
  </button>
</form>
