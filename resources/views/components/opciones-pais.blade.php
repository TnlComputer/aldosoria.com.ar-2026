{{-- Opciones del selector de pais: primero los frecuentes, despues el resto. Props: frecuentes, resto, seleccionado --}}
@props(['frecuentes', 'resto', 'seleccionado' => null])
<option value="">Elegí tu país</option>
<optgroup label="Más frecuentes">
  @foreach ($frecuentes as $pais)
  <option value="{{ $pais->id }}" @selected((string) $seleccionado === (string) $pais->id)>{{ $pais->nombre }}</option>
  @endforeach
</optgroup>
<optgroup label="Todos los países">
  @foreach ($resto as $pais)
  <option value="{{ $pais->id }}" @selected((string) $seleccionado === (string) $pais->id)>{{ $pais->nombre }}</option>
  @endforeach
</optgroup>
