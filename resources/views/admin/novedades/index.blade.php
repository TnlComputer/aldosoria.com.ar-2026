@extends('layouts.app', ['page' => 'Administración · Novedades', 'pageSlug' => 'dashboard'])

@section('content')
<x-admin.pagina titulo="Novedades" :subtitulo="$novedades->total() . ' publicaciones'">
  <x-slot:acciones>
    <a href="{{ route('novedad') }}" class="btn btn-sm btn-secondary" target="_blank" rel="noopener"><i class="tim-icons icon-world"></i> Ver en el sitio</a>
    <a href="{{ route('novedad.create') }}" class="btn btn-primary btn-sm"><i class="tim-icons icon-simple-add"></i> Nueva novedad</a>
  </x-slot:acciones>

  <x-admin.buscador :valor="$busqueda" placeholder="Buscar por título" />

  @if ($novedades->isEmpty())
  <x-admin.vacio texto="No hay novedades que coincidan." />
  @else
  <div class="table-responsive">
    <table class="table admin-tabla">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Novedad</th>
          <th>Origen</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($novedades as $novedad)
        <tr>
          <td data-label="Fecha" class="text-nowrap">{{ \Carbon\Carbon::parse($novedad->fecha)->format('d-m-Y') }}</td>
          <td>
            <span class="admin-principal">{{ $novedad->novedades }}</span>
            <a class="admin-secundario admin-texto-largo d-block" href="{{ $novedad->uri_novedad }}" target="_blank" rel="noopener noreferrer">{{ $novedad->uri_novedad }}</a>
          </td>
          <td data-label="Origen" class="text-nowrap">
            <span class="admin-badge {{ $novedad->tipo_novedad == 1 ? 'admin-badge-info' : 'admin-badge-mal' }}">{{ \App\Http\Controllers\AdminNovedadesController::ORIGENES[$novedad->tipo_novedad] ?? 'Otro' }}</span>
            <span class="admin-badge">{{ \App\Http\Controllers\AdminNovedadesController::TIPOS[$novedad->foto_novedad] ?? 'Video' }}</span>
          </td>
          <td class="admin-celda-acciones">
            <x-admin.editar :href="route('novedad.edit', $novedad->id)" />
            <x-admin.borrar :action="route('novedad.destroy', $novedad->id)" :nombre="$novedad->novedades" que="la novedad" />
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="admin-paginacion">{{ $novedades->links() }}</div>
  @endif
</x-admin.pagina>
@endsection
