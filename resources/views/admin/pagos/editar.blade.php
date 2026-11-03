@extends('layouts.app', ['page' => 'Administración · Editar compra', 'pageSlug' => 'dashboard'])

@section('content')
<x-admin.pagina titulo="Editar compra" :subtitulo="trim($pago->user_name . ' ' . $pago->user_last_name) . ' — ' . $pago->user_email" :volver="route('pagos.show', $pago->user_curso_id)">
  <div class="row mb-3">
    <div class="col-md-6 mb-3">
      <h5 class="admin-seccion-form">Curso</h5>
      <p class="admin-principal mb-1">{{ $pago->nombre_curso }}</p>
      <p class="admin-subtitulo">{{ $pago->titulo_curso }}</p>
    </div>
    <div class="col-md-6 mb-3">
      <h5 class="admin-seccion-form">Detalle del pago</h5>
      <p class="mb-1">Fecha: {{ \Carbon\Carbon::parse($pago->fecha_inscripcion)->format('d-m-Y') }}</p>
      <p class="mb-1">Forma: {{ \App\Http\Controllers\AdminPagoController::FORMAS_PAGO[$pago->forma_pago_curso] ?? 'Otro' }}
        @if ((float) $pago->amount > 0) · {{ $pago->currency }} {{ number_format((float) $pago->amount, 2, ',', '.') }} @endif
      </p>
      @if ($pago->payment_id)
      <p class="mb-1 admin-subtitulo">Pago N° {{ $pago->payment_id }} @if ($pago->orderId) · Orden {{ $pago->orderId }} @endif</p>
      @endif
      <p class="mb-0">Acceso: <x-admin.acceso :vto="$pago->vto_curso" /></p>
    </div>
  </div>

  <form method="post" action="{{ route('pagos.update', $pago->id) }}" class="admin-form">
    @csrf
    @method('put')

    <div class="form-row">
      <x-admin.campo name="vto_curso" label="Extender acceso (días desde hoy)" type="number" min="1" max="3650" placeholder="Dejalo vacío para no cambiarlo"
        ayuda="Ej.: 15 = dos semanas, 365 = un año. Reemplaza el vencimiento actual." />
      <x-admin.campo name="pago_curso" label="Estado del pago" type="select" :value="strtoupper((string) $pago->pago_curso) === 'I' ? 'I' : 'P'"
        :opciones="['P' => 'Pagado', 'I' => 'Impago']" required />
    </div>

    <x-admin.botones-form :cancelar="route('pagos.show', $pago->user_curso_id)" />
  </form>
</x-admin.pagina>
@endsection
