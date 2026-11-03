@php($nuevo = !$marca->exists)

@extends('layouts.app', ['page' => 'Administración · ' . ($nuevo ? 'Nueva marca' : 'Editar marca'), 'pageSlug' => 'dashboard'])

@section('content')
<x-admin.pagina :titulo="$nuevo ? 'Nueva marca' : 'Editar marca'" :subtitulo="$nuevo ? null : $marca->marca" :volver="route('marcas.index')">
  <form method="post" action="{{ $nuevo ? route('marcas.store') : route('marcas.update', $marca->id) }}" enctype="multipart/form-data" class="admin-form">
    @csrf
    @unless ($nuevo) @method('put') @endunless

    <div class="form-row">
      <x-admin.campo name="marca" label="Nombre de la marca" :value="$marca->marca" required maxlength="100"
        :ayuda="$nuevo ? null : 'Si cambiás el nombre, las fichas de chiptuning de esta marca se actualizan solas.'" />
      <x-admin.imagen name="imagen" label="Logo" :required="$nuevo"
        :actual="$nuevo ? null : asset(\App\Http\Controllers\AdminMarcasController::CARPETA . '/' . $marca->foto)" />
    </div>

    <x-admin.botones-form :cancelar="route('marcas.index')" :texto="$nuevo ? 'Crear marca' : 'Guardar cambios'" />
  </form>
</x-admin.pagina>
@endsection
