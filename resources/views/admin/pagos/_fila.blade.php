{{-- Fila de una compra. Variables: $pago, $conAlumno --}}
<tr>
  <td data-label="Fecha" class="text-nowrap">{{ \Carbon\Carbon::parse($pago->fecha_inscripcion)->format('d-m-Y') }}</td>
  @if ($conAlumno)
  <td>
    <a href="{{ route('pagos.show', $pago->user_curso_id) }}" class="admin-principal">{{ trim($pago->user_last_name . ', ' . $pago->user_name, ', ') }}</a>
    <span class="admin-secundario">{{ $pago->user_email }}</span>
  </td>
  @endif
  <td>
    <span @class(['admin-principal' => !$conAlumno])>{{ $pago->nombre_curso }}</span>
    <span class="admin-secundario">{{ $pago->titulo_curso }}</span>
  </td>
  <td data-label="Pago">
    <span class="admin-badge">{{ \App\Http\Controllers\AdminPagoController::FORMAS_PAGO[$pago->forma_pago_curso] ?? 'Otro' }}</span>
    @if (strtoupper((string) $pago->pago_curso) === 'I')
    <span class="admin-badge admin-badge-aviso">Impago</span>
    @endif
  </td>
  <td data-label="Importe" class="text-right text-nowrap">
    @if ((float) $pago->amount > 0)
    {{ $pago->currency }} {{ number_format((float) $pago->amount, 2, ',', '.') }}
    @else
    <span class="admin-secundario">—</span>
    @endif
  </td>
  <td data-label="Acceso"><x-admin.acceso :vto="$pago->vto_curso" /></td>
  <td class="admin-celda-acciones">
    <x-admin.editar :href="route('pagos.edit', $pago->id)" titulo="Editar / extender acceso" />
  </td>
</tr>
