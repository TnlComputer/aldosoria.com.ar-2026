@extends('layouts.app', ['page' => 'Administración', 'pageSlug' => 'dashboard'])

@section('content')
<x-admin.pagina :titulo="'Hola, ' . Auth::user()->name" subtitulo="Resumen del sitio">
  <div class="admin-resumen">
    <a class="admin-dato" href="{{ route('usuarios.index') }}">
      <i class="tim-icons icon-single-02"></i>
      <span class="admin-dato-numero">{{ number_format($datos['alumnos'], 0, ',', '.') }}</span>
      <span class="admin-dato-texto">alumnos registrados · {{ $datos['nuevos'] }} nuevos en 30 días</span>
    </a>
    <a class="admin-dato" href="{{ route('pagos.index') }}">
      <i class="tim-icons icon-coins"></i>
      <span class="admin-dato-numero">{{ $datos['ventas30'] }}</span>
      <span class="admin-dato-texto">compras en los últimos 30 días</span>
    </a>
    <a class="admin-dato" href="{{ route('pagos.index', ['acceso' => 'vigente']) }}">
      <i class="tim-icons icon-check-2"></i>
      <span class="admin-dato-numero">{{ $datos['vigentes'] }}</span>
      <span class="admin-dato-texto">accesos vigentes · {{ $datos['porVencer'] }} vencen esta semana</span>
    </a>
    <a class="admin-dato" href="{{ route('cursos.index') }}">
      <i class="tim-icons icon-video-66"></i>
      <span class="admin-dato-numero">{{ $datos['cursos'] }}</span>
      <span class="admin-dato-texto">cursos publicados</span>
    </a>
  </div>

  <div class="admin-encabezado">
    <h4 class="admin-titulo">Últimas compras</h4>
    <div class="admin-acciones">
      <a href="{{ route('pagos.create') }}" class="btn btn-sm btn-primary"><i class="tim-icons icon-simple-add"></i> Asignar curso</a>
      <a href="{{ route('pagos.index') }}" class="btn btn-sm btn-secondary">Ver todas</a>
    </div>
  </div>

  @if ($ultimas->isEmpty())
  <x-admin.vacio texto="Todavía no hay compras." />
  @else
  <div class="table-responsive">
    <table class="table admin-tabla">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Alumno</th>
          <th>Curso</th>
          <th>Pago</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($ultimas as $compra)
        <tr>
          <td data-label="Fecha" class="text-nowrap">{{ \Carbon\Carbon::parse($compra->fecha_inscripcion)->format('d-m-Y') }}</td>
          <td><a class="admin-principal" href="{{ route('pagos.show', $compra->user_curso_id) }}">{{ trim($compra->last_name . ', ' . $compra->name, ', ') }}</a></td>
          <td>
            {{ $compra->nombre_curso }}
            <span class="admin-secundario">{{ $compra->titulo_curso }}</span>
          </td>
          <td data-label="Pago"><span class="admin-badge">{{ \App\Http\Controllers\AdminPagoController::FORMAS_PAGO[$compra->forma_pago_curso] ?? 'Otro' }}</span></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif
</x-admin.pagina>
@endsection
