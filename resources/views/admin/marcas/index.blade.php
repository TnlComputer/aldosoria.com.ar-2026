@extends('layouts.app', ['page' => 'Administración · Marcas', 'pageSlug' => 'dashboard'])

@section('content')
<x-admin.pagina titulo="Marcas" :subtitulo="$marcas->total() . ' marcas de vehículos'">
  <x-slot:acciones>
    <a href="{{ route('marcas.create') }}" class="btn btn-primary btn-sm"><i class="tim-icons icon-simple-add"></i> Nueva marca</a>
  </x-slot:acciones>

  <x-admin.buscador :valor="$busqueda" placeholder="Buscar marca" />

  @if ($marcas->isEmpty())
  <x-admin.vacio texto="No hay marcas que coincidan." />
  @else
  <div class="table-responsive">
    <table class="table admin-tabla">
      <thead>
        <tr>
          <th>Logo</th>
          <th>Marca</th>
          <th class="text-center">Fichas</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($marcas as $marca)
        <tr>
          <td class="admin-celda-imagen">
            <img class="admin-logo" src="{{ asset(\App\Http\Controllers\AdminMarcasController::CARPETA . '/' . $marca->foto) }}" alt="{{ $marca->marca }}" loading="lazy">
          </td>
          <td><span class="admin-principal">{{ $marca->marca }}</span></td>
          <td class="text-center" data-label="Fichas">
            @if (($fichas[$marca->marca] ?? 0) > 0)
            <a class="admin-badge admin-badge-info" href="{{ route('chiptuning.index', ['marca' => $marca->marca]) }}" title="Ver fichas">{{ $fichas[$marca->marca] }}</a>
            @else
            <span class="admin-badge">0</span>
            @endif
          </td>
          <td class="admin-celda-acciones">
            <x-admin.editar :href="route('marcas.edit', $marca->id)" />
            @unless (($fichas[$marca->marca] ?? 0) > 0)
            <x-admin.borrar :action="route('marcas.destroy', $marca->id)" :nombre="$marca->marca" que="la marca" />
            @endunless
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="admin-paginacion">{{ $marcas->links() }}</div>
  @endif
</x-admin.pagina>
@endsection
