@php($nuevo = !$chip->exists)
@php($carpeta = 'black/img/Graficos_Banco_Pruebas/')

@extends('layouts.app', ['page' => 'Administración · ' . ($nuevo ? 'Nueva ficha' : 'Editar ficha'), 'pageSlug' => 'dashboard'])

@section('content')
<x-admin.pagina :titulo="$nuevo ? 'Nueva ficha de chiptuning' : 'Editar ficha'" :subtitulo="$nuevo ? null : $chip->marca . ' ' . $chip->modelo . ' ' . $chip->motor" :volver="route('chiptuning.index')">
  <form method="post" action="{{ $nuevo ? route('chiptuning.store') : route('chiptuning.update', $chip->id) }}" enctype="multipart/form-data" class="admin-form">
    @csrf
    @unless ($nuevo) @method('put') @endunless

    <h5 class="admin-seccion-form">Vehículo</h5>
    <div class="form-row">
      <x-admin.campo name="marca" label="Marca" type="select" :value="$chip->marca" :opciones="$marcas" vacio="Elegí la marca" required col="col-md-4"
        ayuda="Si falta la marca, cargala primero en Marcas." />
      <x-admin.campo name="modelo" label="Modelo" :value="$chip->modelo" required maxlength="255" col="col-md-4" placeholder="Ej.: Golf GTI MK7" />
      <x-admin.campo name="motor" label="Motor" :value="$chip->motor" maxlength="255" col="col-md-4" placeholder="Ej.: 2.0 TSI" />
      <x-admin.campo name="modelo_motor" label="Código de motor" :value="$chip->modelo_motor" maxlength="255" col="col-md-3" placeholder="Ej.: EA888" />
      <x-admin.campo name="anio" label="Año" :value="$chip->anio" maxlength="30" col="col-md-3" placeholder="Ej.: 2005-2013" />
      <x-admin.campo name="ecu" label="ECU" :value="$chip->ecu" maxlength="255" col="col-md-2" placeholder="Ej.: MED 17.5" />
      <x-admin.campo name="combustible" label="Combustible" :value="$chip->combustible" maxlength="30" col="col-md-2" list="combustibles" />
      <x-admin.campo name="transmicion" label="Transmisión" :value="$chip->transmicion" maxlength="50" col="col-md-2" list="transmisiones" />
      <datalist id="combustibles"><option value="Gasolina"><option value="Diesel"><option value="GNC"></datalist>
      <datalist id="transmisiones"><option value="Manual"><option value="Automática"><option value="DSG"><option value="CVT"></datalist>
    </div>

    <h5 class="admin-seccion-form">Original</h5>
    <div class="form-row">
      <x-admin.campo name="potencia_original" label="Potencia original" :value="$chip->potencia_original" maxlength="50" placeholder="Ej.: 180 HP"
        ayuda="Se guarda siempre como «180 HP». Si es un rango: 115-120 HP." />
      <x-admin.campo name="torque_original" label="Torque original" :value="$chip->torque_original" maxlength="50" placeholder="Ej.: 235 Nm"
        ayuda="Se guarda siempre como «235 Nm»." />
    </div>

    @foreach ([1, 2, 3] as $n)
    <h5 class="admin-seccion-form">Stage {{ $n }}</h5>
    <div class="form-row">
      <div class="col-md-7">
        <div class="form-row">
          <x-admin.campo :name="'stage' . $n" label="Potencia" :value="$chip->{'stage' . $n}" maxlength="30" />
          <x-admin.campo :name="'torque_stg' . $n" label="Torque" :value="$chip->{'torque_stg' . $n}" maxlength="30" />
          <x-admin.campo :name="'configuracion_stg' . $n" label="Configuración" type="textarea" :value="$chip->{'configuracion_stg' . $n}" col="col-12" maxlength="255"
            placeholder="Cambios necesarios: escape, intercooler, etc." />
        </div>
      </div>
      <x-admin.imagen :name="'img_st' . $n" label="Gráfico del banco de pruebas" col="col-md-5"
        :actual="$chip->{'img_st' . $n} ? asset($carpeta . $chip->{'img_st' . $n}) : null" />
    </div>
    @endforeach

    <x-admin.botones-form :cancelar="route('chiptuning.index')" :texto="$nuevo ? 'Crear ficha' : 'Guardar cambios'" />
  </form>
</x-admin.pagina>
@endsection
