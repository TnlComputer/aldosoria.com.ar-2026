@props(['name', 'label', 'actual' => null, 'required' => false, 'col' => 'col-md-6', 'ayuda' => 'JPG, PNG, WEBP o GIF. Máximo 10 MB.', 'accept' => 'image/jpeg,image/png,image/webp,image/gif'])

<div class="form-group {{ $col }}">
  <label for="{{ $name }}">
    {{ $label }}@if ($required)<span class="admin-requerido" aria-hidden="true">*</span>@endif
  </label>
  <div class="admin-imagen">
    <img src="{{ $actual }}" alt="Vista previa" data-preview-de="{{ $name }}" @class(['d-none' => !$actual])>
    @unless ($actual)
    <span class="admin-imagen-vacia" data-vacio-de="{{ $name }}"><i class="tim-icons icon-image-02"></i> Sin imagen</span>
    @endunless
  </div>
  <input type="file" name="{{ $name }}" id="{{ $name }}" accept="{{ $accept }}" data-preview="{{ $name }}" @required($required)
    @class(['form-control-file admin-archivo', 'is-invalid' => $errors->has($name)])>
  <small class="form-text admin-ayuda">{{ $ayuda }}@if ($actual) Dejalo vacío para mantener la actual.@endif</small>
  @error($name)
  <div class="invalid-feedback d-block">{{ $message }}</div>
  @enderror
</div>
