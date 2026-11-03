@props(['vto'])

@if (!$vto)
<span class="admin-badge admin-badge-mal">Sin acceso</span>
@else
@php($fecha = \Carbon\Carbon::parse($vto))
@if ($fecha->endOfDay()->isPast())
<span class="admin-badge admin-badge-mal" title="Venció el {{ $fecha->format('d-m-Y') }}">Vencido {{ $fecha->format('d-m-Y') }}</span>
@else
<span class="admin-badge admin-badge-ok" title="Disponible hasta el {{ $fecha->format('d-m-Y') }}">Hasta {{ $fecha->format('d-m-Y') }}</span>
@endif
@endif
