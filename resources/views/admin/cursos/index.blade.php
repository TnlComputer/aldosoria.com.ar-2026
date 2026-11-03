@extends('layouts.app', ['page' => 'Administración · Cursos', 'pageSlug' => 'dashboard'])

@section('content')
<x-admin.pagina titulo="Cursos" :subtitulo="$cursos->total() . ' cursos en el sitio'">
  <x-slot:acciones>
    <a href="{{ route('cursos.create') }}" class="btn btn-primary btn-sm"><i class="tim-icons icon-simple-add"></i> Nuevo curso</a>
  </x-slot:acciones>

  <x-admin.buscador :valor="$busqueda" placeholder="Buscar por título o subtítulo" />

  @if ($cursos->isEmpty())
  <x-admin.vacio :texto="$busqueda !== '' ? 'Ningún curso coincide con la búsqueda.' : 'Todavía no hay cursos cargados.'" />
  @else
  <div class="table-responsive">
    <table class="table admin-tabla">
      <thead>
        <tr>
          <th></th>
          <th>Curso</th>
          <th class="text-center col-opcional">Videos</th>
          <th class="text-center col-opcional">Temas</th>
          <th class="text-center">Alumnos</th>
          <th class="text-right">Precio</th>
          <th class="col-opcional">Tipo</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($cursos as $curso)
        <tr>
          <td class="admin-celda-imagen"><img class="admin-miniatura" src="{{ \App\Models\Curso::urlImagen($curso->image_curso) }}" alt="" loading="lazy"></td>
          <td>
            <span class="admin-principal">{{ $curso->name }}</span>
            <span class="admin-secundario">{{ $curso->title }}</span>
            @if ($curso->video_demo_Uri)
            <span class="admin-secundario"><i class="tim-icons icon-video-66"></i> con video de muestra</span>
            @endif
          </td>
          <td class="text-center col-opcional" data-label="Videos">{{ (int) $curso->videos_curso_cantidad }}</td>
          <td class="text-center col-opcional">
            <a href="{{ route('temario.show', $curso->id) }}" class="admin-badge admin-badge-info" title="Ver temario">{{ $temas[$curso->id] ?? 0 }}</a>
          </td>
          <td class="text-center" data-label="Alumnos">
            <span @class(['admin-badge', 'admin-badge-ok' => ($alumnos[$curso->id] ?? 0) > 0])>{{ $alumnos[$curso->id] ?? 0 }}</span>
          </td>
          <td class="text-right text-nowrap" data-label="Precio">
            @if ($curso->coast <= 0)
            <span class="admin-badge admin-badge-ok">{{ $curso->coast < 0 ? 'Próximamente' : 'Libre' }}</span>
            @else
            {{ $curso->simbolo }} {{ number_format($curso->coast, 0, ',', '.') }}
            @endif
          </td>
          <td class="col-opcional"><span class="admin-secundario">{{ \App\Http\Controllers\AdminCursoController::TIPOS[$curso->tipo_curso_id] ?? '-' }}</span></td>
          <td class="admin-celda-acciones">
            <x-admin.editar :href="route('temario.show', $curso->id)" titulo="Temario" icono="icon-bullet-list-67" />
            <x-admin.editar :href="route('cursos.edit', $curso->id)" />
            @unless (($alumnos[$curso->id] ?? 0) > 0)
            <x-admin.borrar :action="route('cursos.destroy', $curso->id)" :nombre="$curso->name" que="el curso" />
            @endunless
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="admin-paginacion">{{ $cursos->links() }}</div>
  @endif
</x-admin.pagina>
@endsection
