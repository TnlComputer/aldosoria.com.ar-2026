@props([
  'name',
  'label',
  'type' => 'text',
  'value' => null,
  'opciones' => [],
  'placeholder' => null,
  'vacio' => null,
  'required' => false,
  'ayuda' => null,
  'col' => 'col-md-6',
])

@php
  $id = $attributes->get('id', $name);
  $valor = old($name, $value);
  $invalido = $errors->has($name) ? ' is-invalid' : '';
@endphp

<div class="form-group {{ $col }}">
  <label for="{{ $id }}">
    {{ $label }}@if ($required)<span class="admin-requerido" aria-hidden="true">*</span>@endif
  </label>

  @if ($type === 'select')
  <select name="{{ $name }}" id="{{ $id }}" @required($required) {{ $attributes->except('id')->merge(['class' => 'form-control' . $invalido]) }}>
    @if ($vacio !== null)
    <option value="">{{ $vacio }}</option>
    @endif
    @foreach ($opciones as $clave => $texto)
    <option value="{{ $clave }}" @selected((string) $valor === (string) $clave)>{{ $texto }}</option>
    @endforeach
  </select>
  @elseif ($type === 'textarea')
  <textarea name="{{ $name }}" id="{{ $id }}" rows="{{ $attributes->get('rows', 3) }}" placeholder="{{ $placeholder }}" @required($required)
    {{ $attributes->except(['id', 'rows'])->merge(['class' => 'form-control' . $invalido]) }}>{{ $valor }}</textarea>
  @elseif ($type === 'file')
  <input type="file" name="{{ $name }}" id="{{ $id }}" @required($required)
    {{ $attributes->except('id')->merge(['class' => 'form-control-file admin-archivo' . $invalido]) }}>
  @else
  <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" value="{{ $valor }}" placeholder="{{ $placeholder }}" @required($required)
    {{ $attributes->except('id')->merge(['class' => 'form-control' . $invalido]) }}>
  @endif

  @if ($ayuda)
  <small class="form-text admin-ayuda">{{ $ayuda }}</small>
  @endif
  @error($name)
  <div class="invalid-feedback d-block">{{ $message }}</div>
  @enderror
</div>
