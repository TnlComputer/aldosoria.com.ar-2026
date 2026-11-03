@extends('layouts.app', ['page' => 'Administración · Pagos del alumno', 'pageSlug' => 'dashboard'])

@section('content')
<x-admin.pagina :titulo="trim($usuario->name . ' ' . $usuario->last_name)" :subtitulo="$usuario->email . ($usuario->phone ? ' · ' . $usuario->phone : '')" :volver="route('pagos.index')">
  <x-slot:acciones>
    <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-sm btn-secondary"><i class="tim-icons icon-single-02"></i> Ver usuario</a>
    <a href="{{ route('pagos.create', ['usuario' => $usuario->id]) }}" class="btn btn-sm btn-primary"><i class="tim-icons icon-simple-add"></i> Asignar curso</a>
  </x-slot:acciones>

  @if ($pagos->isEmpty())
  <x-admin.vacio texto="Este alumno no tiene cursos comprados." />
  @else
  <div class="table-responsive">
    <table class="table admin-tabla">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Curso</th>
          <th>Pago</th>
          <th class="text-right">Importe</th>
          <th>Acceso</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($pagos as $pago)
        @include('admin.pagos._fila', ['conAlumno' => false])
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="admin-paginacion">{{ $pagos->links() }}</div>
  @endif
</x-admin.pagina>
@endsection
