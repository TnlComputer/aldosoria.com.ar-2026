@extends('layouts.app', ['page' => 'Administración · Monedas', 'pageSlug' => 'dashboard'])

@section('content')
<x-admin.pagina titulo="Monedas y cotizaciones" subtitulo="La cotización convierte el precio de los cursos a pesos para MercadoPago.">
  <x-slot:acciones>
    <a href="{{ route('monedas.create') }}" class="btn btn-primary btn-sm"><i class="tim-icons icon-simple-add"></i> Nueva moneda</a>
  </x-slot:acciones>

  @if ($monedas->isEmpty())
  <x-admin.vacio texto="Todavía no hay monedas cargadas." />
  @else
  <div class="table-responsive">
    <table class="table admin-tabla">
      <thead>
        <tr>
          <th>Moneda</th>
          <th class="col-opcional">País</th>
          <th class="text-right">Cotización (AR$)</th>
          <th class="text-center">Cursos</th>
          <th class="col-opcional">Actualizada</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($monedas as $moneda)
        <tr>
          <td>
            <span class="admin-principal">{{ $moneda->simbolo }} {{ $moneda->moneda }}</span>
            <span class="admin-secundario">{{ $moneda->name }}</span>
          </td>
          <td class="col-opcional">{{ $moneda->pais }}</td>
          <td class="text-right text-nowrap admin-principal" data-label="Cotización">$ {{ number_format((float) $moneda->cambio, 2, ',', '.') }}</td>
          <td class="text-center" data-label="Cursos"><span class="admin-badge">{{ $cursos[$moneda->id] ?? 0 }}</span></td>
          <td class="col-opcional"><span class="admin-secundario">{{ optional($moneda->updated_at)->format('d-m-Y H:i') }}</span></td>
          <td class="admin-celda-acciones">
            <x-admin.editar :href="route('monedas.edit', $moneda->id)" titulo="Editar cotización" />
            @unless (($cursos[$moneda->id] ?? 0) > 0)
            <x-admin.borrar :action="route('monedas.destroy', $moneda->id)" :nombre="$moneda->moneda" que="la moneda" />
            @endunless
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif
</x-admin.pagina>
@endsection
