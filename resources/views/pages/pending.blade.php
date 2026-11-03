@extends('layouts.app', ['page' => __('Cursos'), 'pageSlug' => 'Cursos'])
@section('content')
<section id="sec-Cur" class="my-5">
  <div class=" bg-current">
    <div class="text-center text-white">
      <h1>SU PAGO EST&Aacute; EN PROCESO</h1>
      <hr>
      <p>Cuando Mercado Pago lo apruebe, el curso aparecer&aacute; en su Dashboard.</p>
      <p>En unos segundos ser&aacute; redirigido hacia su Dashboard</p>
    </div>
  </div>
</section>


<meta http-equiv="refresh" content="5; url={{ route('dashboard') }}" />
@endsection
