@php($nuevo = !$usuario->exists)

@extends('layouts.app', ['page' => 'Administración · ' . ($nuevo ? 'Nuevo usuario' : 'Editar usuario'), 'pageSlug' => 'dashboard'])

@section('content')
<x-admin.pagina :titulo="$nuevo ? 'Nuevo usuario' : 'Editar usuario'" :subtitulo="$nuevo ? 'La cuenta queda activa y con el email verificado.' : $usuario->email" :volver="route('usuarios.index')">
  @unless ($nuevo)
  <x-slot:acciones>
    <a href="{{ route('pagos.show', $usuario->id) }}" class="btn btn-sm btn-secondary"><i class="tim-icons icon-coins"></i> Cursos y pagos</a>
  </x-slot:acciones>
  @endunless

  <form method="post" action="{{ $nuevo ? route('usuarios.store') : route('usuarios.update', $usuario->id) }}" class="admin-form" autocomplete="off">
    @csrf
    @unless ($nuevo) @method('put') @endunless

    <h5 class="admin-seccion-form">Datos personales</h5>
    <div class="form-row">
      <x-admin.campo name="name" label="Nombre" :value="$usuario->name" required maxlength="50" />
      <x-admin.campo name="last_name" label="Apellido" :value="$usuario->last_name" required maxlength="50" />
      <x-admin.campo name="phone" label="Teléfono" type="tel" :value="$usuario->phone" required maxlength="30" />
      <x-admin.campo name="pais_id" label="País" type="select" :value="$usuario->pais_id" :opciones="$paises" vacio="Sin especificar" />
      <x-admin.campo name="whatsapp" label="¿Usa WhatsApp?" type="select" :value="$usuario->whatsapp" :opciones="['SI' => 'Sí', 'NO' => 'No']" col="col-md-3" />
      <x-admin.campo name="tlgram" label="¿Usa Telegram?" type="select" :value="$usuario->tlgram" :opciones="['SI' => 'Sí', 'NO' => 'No']" col="col-md-3" />
    </div>

    <h5 class="admin-seccion-form">Cuenta</h5>
    <div class="form-row">
      @if ($nuevo)
      <x-admin.campo name="email" label="Email" type="email" required maxlength="150" />
      @endif
      <x-admin.campo name="roles" label="Rol" type="select" :value="$usuario->roles ?? 'ROLE_USER'" :opciones="['ROLE_USER' => 'Alumno', 'ROLE_ADMIN' => 'Administrador']" required
        ayuda="Los administradores tienen acceso completo a este panel." />
      @if ($nuevo)
      <x-admin.campo name="password" label="Contraseña" type="password" required autocomplete="new-password" ayuda="Mínimo 8 caracteres, con letras y números." />
      <x-admin.campo name="password_confirmation" label="Repetir contraseña" type="password" required autocomplete="new-password" />
      @endif
    </div>

    <x-admin.botones-form :cancelar="route('usuarios.index')" :texto="$nuevo ? 'Crear usuario' : 'Guardar cambios'" />
  </form>
</x-admin.pagina>
@endsection
