@extends('layouts.app', ['page' => 'Chiptuning · Buscá tu vehículo', 'pageSlug' => 'chiptuning'])

@section('content')
<div class="card">
  <div class="card-body">
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-3">
      <div>
        <h3 class="mb-1 text-white">¿Cuánto gana tu vehículo?</h3>
        <p class="mb-0" style="color: rgba(255,255,255,.65)">Potencia y torque medidos en nuestro banco de rodillos, antes y después de cada Stage.</p>
      </div>
      <a href="{{ route('chiptuning') }}" class="btn btn-sm btn-secondary mt-2">Qué es el chiptuning</a>
    </div>

    <livewire:marca-modelo />

    @include('pages.partials.chip-stages')
  </div>
</div>
@endsection
