@extends('layouts.app', ['page' => __('Cursos'), 'pageSlug' => 'cursos'])

@section('content')
<section id="sec-Cur" class="my-5">
  <div class=" bg-current">
    <div class="text-center text-white">
      <h1>GRACIAS POR SU COMPRA</h1>
      <hr>
      <p>En unos segundos ser&aacute; redirigido hacia su Dashboard</p>
    </div>
  </div>
</section>

<meta http-equiv="refresh" content="3; url={{ route('dashboard') }}" />
@endsection