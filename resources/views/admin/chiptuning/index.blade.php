@extends('layouts.app', ['page' => 'Administración · Chiptuning', 'pageSlug' => 'dashboard'])

@section('content')
<x-admin.pagina titulo="Chiptuning" :subtitulo="$chips->total() . ' fichas de vehículos'">
  <x-slot:acciones>
    <a href="{{ route('chiptuning.create') }}" class="btn btn-primary btn-sm"><i class="tim-icons icon-simple-add"></i> Nueva ficha</a>
  </x-slot:acciones>

  <x-admin.buscador :valor="$busqueda" placeholder="Modelo, motor, ECU o año" :filtrando="$busqueda !== '' || $marca !== ''">
    <select name="marca" class="form-control" aria-label="Marca">
      <option value="">Todas las marcas</option>
      @foreach ($marcas as $nombre)
      <option value="{{ $nombre }}" @selected($marca === $nombre)>{{ $nombre }}</option>
      @endforeach
    </select>
  </x-admin.buscador>

  @if ($chips->isEmpty())
  <x-admin.vacio texto="No hay fichas que coincidan." />
  @else
  <div class="table-responsive">
    <table class="table admin-tabla">
      <thead>
        <tr>
          <th>Vehículo</th>
          <th class="col-opcional">ECU</th>
          <th class="col-opcional">Combustible</th>
          <th>Original</th>
          <th>Stages</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($chips as $chip)
        <tr>
          <td>
            <span class="admin-principal">{{ $chip->marca }} {{ $chip->modelo }}</span>
            <span class="admin-secundario">{{ collect([$chip->motorDistinto(), $chip->anio, $chip->transmicion])->map(fn ($v) => trim((string) $v))->filter()->implode(' · ') }}</span>
          </td>
          <td class="col-opcional">{{ $chip->ecu }}</td>
          <td class="col-opcional">{{ $chip->combustible }}</td>
          <td data-label="Original" class="text-nowrap">
            {{ $chip->potencia_original }}
            @if ($chip->torque_original)<span class="admin-secundario">{{ $chip->torque_original }}</span>@endif
          </td>
          <td data-label="Stages">
            @foreach ([1, 2, 3] as $n)
            @if ($chip->{'stage' . $n})
            <span class="admin-badge admin-badge-info" title="Stage {{ $n }}: {{ $chip->{'stage' . $n} }}">S{{ $n }} {{ $chip->{'stage' . $n} }}</span>
            @endif
            @endforeach
          </td>
          <td class="admin-celda-acciones">
            <x-admin.editar :href="route('chiptuning.edit', $chip->id)" />
            <x-admin.borrar :action="route('chiptuning.destroy', $chip->id)" :nombre="$chip->marca . ' ' . $chip->modelo . ' ' . $chip->motor" que="la ficha" />
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="admin-paginacion">{{ $chips->links() }}</div>
  @endif
</x-admin.pagina>
@endsection
