@extends('layouts.app', ['page' => 'Administración · Oferta', 'pageSlug' => 'dashboard'])

@section('content')
<x-admin.pagina titulo="Oferta destacada" subtitulo="Es la imagen que aparece en el menú lateral de todo el sitio.">
  <form method="post" action="{{ route('adminOferta.update', $oferta->id) }}" enctype="multipart/form-data" class="admin-form">
    @csrf
    @method('patch')

    <div class="form-row">
      <x-admin.imagen name="imagen" label="Imagen de la oferta" required accept="image/jpeg" ayuda="Solo JPG. Máximo 10 MB."
        :actual="asset('img/Ofertas/oferta.jpg') . '?v=' . @filemtime(public_path('img/Ofertas/oferta.jpg'))" />
    </div>

    <x-admin.botones-form :cancelar="route('dashboard')" texto="Publicar oferta" />
  </form>
</x-admin.pagina>
@endsection
