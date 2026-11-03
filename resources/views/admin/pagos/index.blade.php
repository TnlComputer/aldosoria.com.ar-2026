@extends('layouts.app', ['page' => 'Administración · Pagos', 'pageSlug' => 'dashboard'])

@section('content')
<x-admin.pagina titulo="Pagos y accesos" :subtitulo="$pagos->total() . ' compras registradas'">
  <x-slot:acciones>
    <a href="{{ route('pagos.create') }}" class="btn btn-primary btn-sm"><i class="tim-icons icon-simple-add"></i> Asignar curso</a>
  </x-slot:acciones>

  <x-admin.buscador :valor="$busqueda" placeholder="Alumno, email o curso" :filtrando="$busqueda !== '' || $forma !== null || $acceso !== null">
    <select name="forma" class="form-control" aria-label="Forma de pago">
      <option value="">Todas las formas de pago</option>
      @foreach (\App\Http\Controllers\AdminPagoController::FORMAS_PAGO as $clave => $texto)
      <option value="{{ $clave }}" @selected($forma !== null && (string) $forma === (string) $clave)>{{ $texto }}</option>
      @endforeach
    </select>
    <select name="acceso" class="form-control" aria-label="Acceso">
      <option value="">Todos los accesos</option>
      <option value="vigente" @selected($acceso === 'vigente')>Vigentes</option>
      <option value="vencido" @selected($acceso === 'vencido')>Vencidos</option>
    </select>
  </x-admin.buscador>

  @if ($pagos->isEmpty())
  <x-admin.vacio texto="No hay compras que coincidan." />
  @else
  <div class="table-responsive">
    <table class="table admin-tabla">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Alumno</th>
          <th>Curso</th>
          <th>Pago</th>
          <th class="text-right">Importe</th>
          <th>Acceso</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($pagos as $pago)
        @include('admin.pagos._fila', ['conAlumno' => true])
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="admin-paginacion">{{ $pagos->links() }}</div>
  @endif
</x-admin.pagina>
@endsection
