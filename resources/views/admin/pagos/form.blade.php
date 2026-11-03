@extends('layouts.app', ['page' => 'Administración · Asignar curso', 'pageSlug' => 'dashboard'])

@section('content')
<x-admin.pagina titulo="Asignar curso a un alumno" subtitulo="Para pagos en efectivo, transferencias o cursos de regalo. PayPal y MercadoPago se acreditan solos."
  :volver="route('pagos.index')">
  <form method="post" action="{{ route('pagos.store') }}" class="admin-form">
    @csrf

    <div class="form-row">
      <div class="form-group col-md-12 mb-1">
        <label for="filtro-alumno">Buscar alumno</label>
        <input type="search" id="filtro-alumno" class="form-control" placeholder="Escribí parte del nombre o del email para filtrar la lista" data-filtrar="user_curso_id" autocomplete="off">
      </div>
      <x-admin.campo name="user_curso_id" label="Alumno" type="select" :value="$usuarioElegido" :opciones="$usuarios" vacio="Elegí el alumno" required col="col-md-12" size="6" />
      <x-admin.campo name="video_curso_id" label="Curso" type="select" :opciones="$cursos" vacio="Elegí el curso" required col="col-md-12" />
      <x-admin.campo name="forma_pago_curso" label="Forma de pago" type="select" :opciones="$formas" :value="2" required col="col-md-4" />
      <x-admin.campo name="amount" label="Importe cobrado (AR$)" type="number" step="0.01" min="0" placeholder="Opcional" col="col-md-4" />
      <x-admin.campo name="vto_curso" label="Días de acceso" type="number" min="1" max="3650" :value="15" required col="col-md-4"
        ayuda="Desde hoy. Ej.: 15 = dos semanas, 365 = un año." />
    </div>

    <x-admin.botones-form :cancelar="route('pagos.index')" texto="Asignar curso" />
  </form>
</x-admin.pagina>
@endsection
