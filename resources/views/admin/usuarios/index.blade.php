@extends('layouts.app', ['page' => 'Administración · Usuarios', 'pageSlug' => 'dashboard'])

@section('content')
<x-admin.pagina titulo="Usuarios" :subtitulo="$usuarios->total() . ($usuarios->total() == 1 ? ' usuario encontrado' : ' usuarios encontrados')">
  <x-slot:acciones>
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary btn-sm"><i class="tim-icons icon-simple-add"></i> Nuevo usuario</a>
  </x-slot:acciones>

  <x-admin.buscador :valor="$busqueda" placeholder="Nombre, apellido, email o teléfono" :filtrando="$busqueda !== '' || $rol !== 'ROLE_USER' || $cursos !== 'todos'">
    <select name="rol" class="form-control" aria-label="Rol">
      <option value="ROLE_USER" @selected($rol === 'ROLE_USER')>Alumnos</option>
      <option value="ROLE_ADMIN" @selected($rol === 'ROLE_ADMIN')>Administradores</option>
      <option value="todos" @selected($rol === 'todos')>Todos</option>
    </select>
    <select name="cursos" class="form-control" aria-label="Cursos">
      <option value="todos" @selected($cursos === 'todos')>Con y sin cursos</option>
      <option value="con" @selected($cursos === 'con')>Con cursos</option>
      <option value="sin" @selected($cursos === 'sin')>Sin cursos</option>
    </select>
  </x-admin.buscador>

  @if ($usuarios->isEmpty())
  <x-admin.vacio :texto="'No hay usuarios que coincidan' . ($busqueda !== '' ? ' con «' . $busqueda . '»' : '') . '.'" />
  @else
  <div class="table-responsive">
    <table class="table admin-tabla">
      <thead>
        <tr>
          <th>Usuario</th>
          <th>Teléfono</th>
          <th class="col-opcional">País</th>
          <th class="text-center">Cursos</th>
          <th class="col-opcional">Alta</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($usuarios as $usuario)
        @php($wa = $usuario->whatsapp === 'SI' ? preg_replace('/\D+/', '', (string) $usuario->phone) : '')
        <tr>
          <td>
            <span class="admin-principal">{{ trim($usuario->last_name . ', ' . $usuario->name, ', ') }}</span>
            @if ($usuario->isAdmin())
            <span class="admin-badge admin-badge-aviso ml-1">Admin</span>
            @endif
            <a class="admin-secundario" href="mailto:{{ $usuario->email }}">{{ $usuario->email }}</a>
          </td>
          <td data-label="Teléfono" class="text-nowrap">
            {{ $usuario->phone }}
            @if (strlen($wa) >= 8)
            <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener noreferrer" class="admin-badge admin-badge-ok ml-1" title="Abrir WhatsApp">WhatsApp</a>
            @endif
          </td>
          <td class="col-opcional">{{ $usuario->pais->nombre ?? '' }}</td>
          <td class="text-center" data-label="Cursos">
            @if ($usuario->cursos_pagos_count > 0)
            <a class="admin-badge admin-badge-ok" href="{{ route('pagos.show', $usuario->id) }}" title="Ver cursos y pagos">{{ $usuario->cursos_pagos_count }}</a>
            @else
            <span class="admin-badge">0</span>
            @endif
          </td>
          <td class="col-opcional"><span class="admin-secundario">{{ optional($usuario->created_at)->format('d-m-Y') }}</span></td>
          <td class="admin-celda-acciones">
            <x-admin.editar :href="route('pagos.show', $usuario->id)" titulo="Cursos y pagos" icono="icon-coins" />
            <x-admin.editar :href="route('usuarios.edit', $usuario->id)" />
            @if ($usuario->id !== auth()->id() && $usuario->cursos_pagos_count == 0)
            <x-admin.borrar :action="route('usuarios.destroy', $usuario->id)" :nombre="$usuario->name . ' ' . $usuario->last_name" que="el usuario" />
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="admin-paginacion">{{ $usuarios->links() }}</div>
  @endif
</x-admin.pagina>
@endsection
