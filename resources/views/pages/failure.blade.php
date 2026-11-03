@extends('layouts.app', ['page' => __('Cursos'), 'pageSlug' => 'Cursos'])
@section('content')
<section id="sec-Cur" class="my-5">
  <div class=" bg-current">
    <div class="text-center text-white">
      <h1>Ops!!! SU PAGO NO FUE COMO ESPERAMOS</h1>
      <hr>
      <p>En unos segundos ser&aacute; redirigido hacia los Cursos</p>
    </div>
  </div>
</section>


<meta http-equiv="refresh" content="3; url={{ route('cursos') }}" />
@endsection