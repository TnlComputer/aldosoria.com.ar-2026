@php($nuevo = !$moneda->exists)

@extends('layouts.app', ['page' => 'Administración · ' . ($nuevo ? 'Nueva moneda' : 'Editar moneda'), 'pageSlug' => 'dashboard'])

@section('content')
<x-admin.pagina :titulo="$nuevo ? 'Nueva moneda' : 'Editar ' . $moneda->moneda" :volver="route('monedas.index')">
  <form method="post" action="{{ $nuevo ? route('monedas.store') : route('monedas.update', $moneda->id) }}" class="admin-form">
    @csrf
    @unless ($nuevo) @method('put') @endunless

    <div class="form-row">
      <x-admin.campo name="moneda" label="Código" :value="$moneda->moneda" required maxlength="3" col="col-md-3" placeholder="EUR" ayuda="3 letras (ISO): EUR, USD, ARS." />
      <x-admin.campo name="simbolo" label="Símbolo" :value="$moneda->simbolo" required maxlength="10" col="col-md-3" placeholder="€" />
      <x-admin.campo name="name" label="Nombre" :value="$moneda->name" required maxlength="50" col="col-md-6" placeholder="Euro" />
      <x-admin.campo name="pais" label="País" :value="$moneda->pais" required maxlength="50" />
      <x-admin.campo name="cambio" label="Cotización en pesos" type="number" step="0.01" min="0" :value="$moneda->cambio" required
        ayuda="Cuántos pesos argentinos equivale 1 unidad de esta moneda." />
    </div>

    <x-admin.botones-form :cancelar="route('monedas.index')" :texto="$nuevo ? 'Crear moneda' : 'Guardar cambios'" />
  </form>
</x-admin.pagina>
@endsection
