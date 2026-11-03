@extends('layouts.app', ['page' => 'Administración · Temario', 'pageSlug' => 'dashboard'])

@section('content')
<x-admin.pagina titulo="Temario" :subtitulo="$curso->name . ' — ' . $curso->title" :volver="route('cursos.index')">
  <x-slot:acciones>
    <a href="{{ route('cursos.edit', $curso->id) }}" class="btn btn-sm btn-secondary"><i class="tim-icons icon-pencil"></i> Editar curso</a>
    <a href="{{ route('temario.create', ['curso' => $curso->id]) }}" class="btn btn-sm btn-primary"><i class="tim-icons icon-simple-add"></i> Agregar tema</a>
  </x-slot:acciones>

  @if ($temarios->isEmpty())
  <x-admin.vacio texto="Este curso todavía no tiene temas.">
    <a href="{{ route('temario.create', ['curso' => $curso->id]) }}" class="btn btn-sm btn-primary">Agregar el primer tema</a>
  </x-admin.vacio>
  @else
  <p class="admin-contador">{{ $temarios->count() }} {{ $temarios->count() === 1 ? 'tema' : 'temas' }}</p>
  <div class="table-responsive">
    <table class="table admin-tabla">
      <thead>
        <tr>
          <th class="text-center">N°</th>
          <th>Tema</th>
          <th>Tipo</th>
          <th class="col-opcional">Contenido</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($temarios as $tema)
        <tr>
          <td class="text-center admin-principal" data-label="N°">{{ $tema->nro_video_id }}</td>
          <td><span class="admin-principal">{{ $tema->descripcion }}</span></td>
          <td data-label="Tipo">
            @if ($tema->tipo_curso == 2)
            <span class="admin-badge admin-badge-aviso"><i class="tim-icons icon-attach-87"></i> Archivo</span>
            @else
            <span class="admin-badge admin-badge-info"><i class="tim-icons icon-video-66"></i> Video</span>
            @endif
          </td>
          <td class="col-opcional">
            @if ($tema->tipo_curso == 2)
            @foreach (array_filter([$tema->archivo1_curso, $tema->archivo2_curso]) as $archivo)
            <span class="admin-secundario"><i class="tim-icons icon-attach-87"></i> {{ $archivo }}</span>
            @endforeach
            @elseif ($tema->uri_video)
            <a class="admin-secundario admin-texto-largo d-block" href="{{ $tema->uri_video }}" target="_blank" rel="noopener noreferrer">{{ $tema->uri_video }}</a>
            @else
            <span class="admin-badge admin-badge-mal">Sin contenido</span>
            @endif
          </td>
          <td class="admin-celda-acciones">
            <x-admin.editar :href="route('temario.edit', $tema->id)" />
            <x-admin.borrar :action="route('temario.destroy', $tema->id)" :nombre="$tema->descripcion" que="el tema" />
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif
</x-admin.pagina>
@endsection
