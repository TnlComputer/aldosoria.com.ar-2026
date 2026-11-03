@php($nuevo = !$curso->exists)

@extends('layouts.app', ['page' => 'Administración · ' . ($nuevo ? 'Nuevo curso' : 'Editar curso'), 'pageSlug' => 'dashboard'])

@section('content')
<x-admin.pagina :titulo="$nuevo ? 'Nuevo curso' : 'Editar curso'" :subtitulo="$nuevo ? null : $curso->name" :volver="route('cursos.index')">
  <form method="post" action="{{ $nuevo ? route('cursos.store') : route('cursos.update', $curso) }}" enctype="multipart/form-data" class="admin-form">
    @csrf
    @unless ($nuevo) @method('patch') @endunless

    <div class="row">
      <div class="col-lg-8">
        <h5 class="admin-seccion-form">Datos del curso</h5>
        <div class="form-row">
          <x-admin.campo name="name" label="Título" :value="$curso->name" required maxlength="100" col="col-md-12" />
          <x-admin.campo name="title" label="Subtítulo" :value="$curso->title" required maxlength="100" col="col-md-12" />
          <x-admin.campo name="tipo_curso_id" label="Tipo de curso" type="select" :value="$curso->tipo_curso_id" :opciones="\App\Http\Controllers\AdminCursoController::TIPOS" required />
          <x-admin.campo name="videos_curso_cantidad" label="Cantidad de videos" type="number" min="0" max="999" :value="(int) $curso->videos_curso_cantidad" required />
          <x-admin.campo name="video_demo_Uri" label="Video de muestra" type="url" :value="$curso->video_demo_Uri" placeholder="https://www.youtube.com/embed/..." col="col-md-12"
            ayuda="Opcional. Usá el enlace para insertar (embed) de YouTube o Vimeo. Si está vacío se muestra la imagen." />
        </div>

        <h5 class="admin-seccion-form">Precio</h5>
        <div class="form-row">
          <x-admin.campo name="moneda_id" label="Moneda" type="select" :value="$curso->moneda_id" :opciones="$monedas" vacio="Elegí una moneda" required />
          <x-admin.campo name="coast" label="Precio" type="number" step="0.01" min="-1" :value="$curso->coast ?? 0" required
            ayuda="0 = curso libre (gratis). -1 = se muestra como próximamente." />
        </div>
      </div>

      <div class="col-lg-4">
        <h5 class="admin-seccion-form">Imagen</h5>
        <div class="form-row">
          <x-admin.imagen name="imagen" label="Imagen del curso" :actual="$nuevo ? null : \App\Models\Curso::urlImagen($curso->image_curso)" col="col-12" />
        </div>
      </div>
    </div>

    <x-admin.botones-form :cancelar="route('cursos.index')" :texto="$nuevo ? 'Crear curso' : 'Guardar cambios'" />
  </form>
</x-admin.pagina>
@endsection
