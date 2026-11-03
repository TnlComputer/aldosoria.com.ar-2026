@php($nuevo = !$temario->exists)
@php($volver = $curso ? route('temario.show', $curso->id) : route('cursos.index'))

@extends('layouts.app', ['page' => 'Administración · ' . ($nuevo ? 'Nuevo tema' : 'Editar tema'), 'pageSlug' => 'dashboard'])

@section('content')
<x-admin.pagina :titulo="$nuevo ? 'Nuevo tema' : 'Editar tema'" :subtitulo="$curso ? $curso->name . ' — ' . $curso->title : null" :volver="$volver">
  <form method="post" action="{{ $nuevo ? route('temario.store') : route('temario.update', $temario) }}" enctype="multipart/form-data" class="admin-form">
    @csrf
    @unless ($nuevo) @method('patch') @endunless

    <div class="form-row">
      @if ($nuevo)
      <x-admin.campo name="id_curso" label="Curso" type="select" :value="$curso?->id" :opciones="$cursos" vacio="Elegí el curso" required col="col-md-8" />
      @endif
      <x-admin.campo name="nro_video_id" label="Número de orden" type="number" min="0" :value="$temario->nro_video_id" col="col-md-4"
        ayuda="Define el orden en que aparece en el temario." />
      <x-admin.campo name="descripcion" label="Título del tema" :value="$temario->descripcion" required maxlength="255" col="col-md-12" />
      <x-admin.campo name="tipo_curso" label="Tipo de contenido" type="select" :value="$temario->tipo_curso ?: 1" :opciones="[1 => 'Video', 2 => 'Archivos descargables']" required col="col-md-4" id="tipo_curso" />
    </div>

    <div id="contenido-video" class="form-row">
      <x-admin.campo name="uri_video" label="URL del video" type="url" :value="$temario->uri_video" placeholder="https://player.vimeo.com/video/..." col="col-md-12"
        ayuda="Enlace para insertar (embed) de Vimeo o YouTube. Solo lo ven los alumnos que compraron el curso." />
    </div>

    <div id="contenido-archivos" class="form-row">
      @php($aceptados = '.' . str_replace(',', ',.', \App\Support\Subidas::EXTENSIONES_ARCHIVO_CURSO))
      @php($maximo = \App\Support\Subidas::maximoArchivoMb())
      <x-admin.campo name="archivo1_curso" label="Archivo 1" type="file" :accept="$aceptados" data-max-mb="{{ $maximo }}"
        :ayuda="($temario->archivo1_curso ? 'Actual: ' . $temario->archivo1_curso . '. ' : '') . 'RAR, ZIP, PDF, Office o archivos de ECU (BIN, ORI, HEX, OLS…). Máximo ' . $maximo . ' MB.'" />
      <x-admin.campo name="archivo2_curso" label="Archivo 2" type="file" :accept="$aceptados" data-max-mb="{{ $maximo }}"
        :ayuda="($temario->archivo2_curso ? 'Actual: ' . $temario->archivo2_curso . '. ' : '') . 'Opcional. Máximo ' . $maximo . ' MB.'" />
    </div>

    <x-admin.botones-form :cancelar="$volver" :texto="$nuevo ? 'Agregar tema' : 'Guardar cambios'" />
  </form>
</x-admin.pagina>

<script>
  (function () {
    var tipo = document.getElementById('tipo_curso');
    function mostrar() {
      document.getElementById('contenido-video').hidden = tipo.value === '2';
      document.getElementById('contenido-archivos').hidden = tipo.value !== '2';
    }
    tipo.addEventListener('change', mostrar);
    mostrar();
  })();
</script>
@endsection
