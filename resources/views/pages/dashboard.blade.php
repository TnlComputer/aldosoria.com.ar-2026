@extends('layouts.app', ['page' => 'Mis cursos', 'pageSlug' => 'dashboard', 'noindex' => true])

@php
  $usuario = Auth::user();
  $hoy = \Carbon\Carbon::today();
  // Estado de cada curso de esta pagina: vigente, por vencer (3 dias o menos) o vencido
  $estados = $cursosPagos->mapWithKeys(function ($c) use ($hoy) {
    $vto = \Carbon\Carbon::parse($c->vto_curso)->startOfDay();
    $dias = (int) $hoy->diffInDays($vto, false);
    return [$c->id => ['vto' => $vto, 'dias' => $dias, 'vencido' => $dias < 0, 'porVencer' => $dias >= 0 && $dias <= 3]];
  });
  $vigentes = $estados->where('vencido', false)->count();
  $proximo = $estados->where('vencido', false)->sortBy('dias')->first();
@endphp

@section('content')
<div class="card">
  <div class="card-body">

    <div class="panel-cabecera">
      <div>
        <p class="curso-sobretitulo">Mi panel</p>
        <h1>Hola, {{ $usuario->name }}</h1>
        @if ($cursosPagos->total() > 0)
        <p class="panel-resumen">
          {{ $vigentes }} {{ $vigentes == 1 ? 'curso disponible' : 'cursos disponibles' }}
          @if ($proximo)
          · el próximo vence {{ $proximo['dias'] === 0 ? 'hoy' : ($proximo['dias'] === 1 ? 'mañana' : 'en ' . $proximo['dias'] . ' días') }}
          @endif
        </p>
        @endif
      </div>
      <div class="panel-accesos">
        <a href="{{ route('cursos') }}" class="btn btn-sm btn-secondary"><i class="fas fa-search" aria-hidden="true"></i> Ver más cursos</a>
        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-secondary"><i class="fas fa-user" aria-hidden="true"></i> Mi perfil</a>
      </div>
    </div>

    @if ($cursosPagos->isEmpty())
    <div class="panel-vacio">
      <i class="fas fa-graduation-cap" aria-hidden="true"></i>
      <h2>Todavía no tenés cursos</h2>
      <p>Cuando compres un curso va a aparecer acá, listo para ver sus clases.</p>
      <a href="{{ route('cursos') }}" class="btn btn-primary">Ver cursos disponibles</a>
    </div>
    @else
    <div class="panel-cursos">
      @foreach ($cursosPagos as $cursoPago)
      @php
        $estado = $estados[$cursoPago->id];
        $clases = $temarios->where('video_curso_id', $cursoPago->video_curso_id);
        $videos = $clases->where('tipo_curso', '!=', 2)->count();
        $archivos = $clases->where('tipo_curso', 2)->count();
      @endphp
      <article @class(['panel-curso', 'panel-curso-vencido' => $estado['vencido']])>
        <div class="panel-curso-imagen">
          <img src="{{ \App\Models\Curso::urlImagen($cursoPago->image_curso) }}" alt="" loading="lazy">
          @if ($estado['vencido'])
          <span class="curso-etiqueta">Vencido</span>
          @elseif ($estado['porVencer'])
          <span class="curso-etiqueta curso-etiqueta-aviso">{{ $estado['dias'] === 0 ? 'Vence hoy' : 'Vence en ' . $estado['dias'] . ($estado['dias'] === 1 ? ' día' : ' días') }}</span>
          @else
          <span class="curso-etiqueta curso-etiqueta-tuyo">Disponible</span>
          @endif
        </div>

        <div class="panel-curso-cuerpo">
          <h2>{{ $cursoPago->name }}</h2>
          @if ($cursoPago->title)
          <p class="panel-curso-subtitulo">{{ $cursoPago->title }}</p>
          @endif

          <ul class="curso-datos">
            @if ($videos)
            <li><i class="fas fa-play-circle" aria-hidden="true"></i> {{ $videos }} {{ $videos == 1 ? 'clase' : 'clases' }}</li>
            @endif
            @if ($archivos)
            <li><i class="fas fa-download" aria-hidden="true"></i> {{ $archivos }} con archivos</li>
            @endif
            <li><i class="fas fa-calendar-alt" aria-hidden="true"></i>
              {{ $estado['vencido'] ? 'Venció el' : 'Hasta el' }} {{ $estado['vto']->format('d/m/Y') }}</li>
          </ul>

          @if ($clases->isNotEmpty())
          <details class="panel-temario">
            <summary>Ver temario</summary>
            <ol>
              @foreach ($clases as $clase)
              <li>{{ $clase->descripcion }}</li>
              @endforeach
            </ol>
          </details>
          @endif

          <div class="panel-curso-acciones">
            @if ($estado['vencido'])
            <a href="https://wa.me/{{ config('services.whatsapp') }}?text={{ rawurlencode('Hola! Quiero renovar el acceso al curso ' . $cursoPago->name) }}" class="btn chip-whatsapp" target="_blank" rel="noopener noreferrer">
              <img src="{{ asset('black/img/social/whatsapp.svg') }}" alt="" width="18" height="18"> Renovar acceso
            </a>
            <a href="{{ route('cursoDetalle.show', $cursoPago->video_curso_id) }}" class="btn btn-secondary">Ver el curso</a>
            @else
            <a href="{{ route('video.show', $cursoPago->video_curso_id) }}" class="btn btn-primary"><i class="fas fa-play" aria-hidden="true"></i> Entrar al curso</a>
            @endif
          </div>
        </div>
      </article>
      @endforeach
    </div>

    <div class="mt-4">{{ $cursosPagos->links() }}</div>
    @endif

    <p class="panel-ayuda">
      ¿Problemas para ver un curso?
      <a href="https://wa.me/{{ config('services.whatsapp') }}?text={{ rawurlencode('Hola! Tengo un problema para ver un curso') }}" target="_blank" rel="noopener noreferrer">Escribinos por WhatsApp</a>
      o a <a href="mailto:{{ config('mail.admin_address') }}">{{ config('mail.admin_address') }}</a>.
    </p>
  </div>
</div>
@endsection
