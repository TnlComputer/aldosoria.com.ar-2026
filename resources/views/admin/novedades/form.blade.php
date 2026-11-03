@php($nuevo = !$novedad->exists)

@extends('layouts.app', ['page' => 'Administración · ' . ($nuevo ? 'Nueva novedad' : 'Editar novedad'), 'pageSlug' => 'dashboard'])

@section('content')
<x-admin.pagina :titulo="$nuevo ? 'Nueva novedad' : 'Editar novedad'" :volver="route('novedad.index')">
  <form method="post" action="{{ $nuevo ? route('novedad.store') : route('novedad.update', $novedad->id) }}" class="admin-form">
    @csrf
    @unless ($nuevo) @method('put') @endunless

    <div class="form-row">
      <x-admin.campo name="novedades" label="Título" :value="$novedad->novedades" required maxlength="255" col="col-md-9" />
      <x-admin.campo name="fecha" label="Fecha" type="date" :value="$novedad->fecha ? \Carbon\Carbon::parse($novedad->fecha)->toDateString() : null" required col="col-md-3" />
      <x-admin.campo name="uri_novedad" label="Enlace del video o publicación" type="url" :value="$novedad->uri_novedad" required col="col-md-12"
        placeholder="https://www.youtube.com/embed/..." ayuda="Para YouTube usá el enlace para insertar: Compartir › Insertar." />
      <x-admin.campo name="tipo_novedad" label="Origen" type="select" :value="$novedad->tipo_novedad" :opciones="\App\Http\Controllers\AdminNovedadesController::ORIGENES" required />
      <x-admin.campo name="foto_novedad" label="Tipo" type="select" :value="$novedad->foto_novedad" :opciones="\App\Http\Controllers\AdminNovedadesController::TIPOS" required />
    </div>

    <x-admin.botones-form :cancelar="route('novedad.index')" :texto="$nuevo ? 'Publicar' : 'Guardar cambios'" />
  </form>
</x-admin.pagina>
@endsection
