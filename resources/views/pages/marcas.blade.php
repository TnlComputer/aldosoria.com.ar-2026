@extends('layouts.app', [
  'page' => $seoPagina ?? 'Chiptuning · Buscá tu vehículo',
  'pageSlug' => 'chiptuning',
  'description' => $seoDescripcion ?? 'Buscá tu vehículo y mirá cuánta potencia y torque gana con chiptuning Stage 1, 2 y 3. Fichas medidas en banco de rodillos en Soria Perfomance, Morón.',
  'canonical' => $seoCanonical ?? null,
])

@section('content')
<div class="card">
  <div class="card-body">
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-3">
      <div>
        <h1 class="h3 mb-1 text-white">¿Cuánto gana tu vehículo?</h1>
        <p class="mb-0" style="color: rgba(255,255,255,.65)">Potencia y torque medidos en nuestro banco de rodillos, antes y después de cada Stage.</p>
      </div>
      <a href="{{ route('chiptuning') }}" class="btn btn-sm btn-secondary mt-2">Qué es el chiptuning</a>
    </div>

    <livewire:marca-modelo />

    @include('pages.partials.chip-stages')
  </div>
</div>
@endsection
