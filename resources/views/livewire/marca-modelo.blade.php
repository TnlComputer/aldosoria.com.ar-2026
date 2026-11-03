<div class="chip">
  {{-- Buscador --}}
  <div class="chip-buscador">
    <i class="tim-icons icon-zoom-split"></i>
    <input type="search" class="form-control" placeholder="Buscá tu vehículo: modelo, motor o ECU (ej.: Golf 1.8T, EDC15)"
      wire:model.live.debounce.400ms="buscar" maxlength="60" aria-label="Buscar vehículo">
    <span class="chip-cargando" wire:loading>Buscando…</span>
  </div>

  @if ($vehiculo)
  {{-- Ficha del vehiculo --}}
  @php($etapas = $vehiculo->etapas())
  @php($maximo = max(array_map(fn ($e) => (int) preg_replace('/\D.*/', '', (string) $e['potencia']), $etapas)) ?: 1)
  @php($mensaje = 'Hola! Quiero consultar por la reprogramación del ' . trim($vehiculo->marca . ' ' . $vehiculo->modelo . ' ' . $vehiculo->motor . ' ' . $vehiculo->anio))

  <div class="chip-ficha" wire:key="ficha-{{ $vehiculo->id }}">
    <button type="button" class="chip-volver" wire:click="volver"><i class="tim-icons icon-minimal-left"></i> Ver todos los modelos de {{ $vehiculo->marca }}</button>

    <div class="chip-ficha-encabezado">
      <div>
        <p class="chip-ficha-marca">{{ $vehiculo->marca }}</p>
        <h3 class="chip-ficha-titulo">{{ $vehiculo->modelo }}</h3>
        <div class="chip-datos">
          @php($codigo = in_array(mb_strtolower(trim((string) $vehiculo->modelo_motor)), [mb_strtolower(trim((string) $vehiculo->modelo)), mb_strtolower(trim((string) $vehiculo->motor))]) ? null : $vehiculo->modelo_motor)
          @foreach (['Motor' => $vehiculo->motorDistinto(), 'Código' => $codigo, 'Año' => $vehiculo->anio, 'ECU' => $vehiculo->ecu, 'Combustible' => $vehiculo->combustible, 'Transmisión' => $vehiculo->transmicion] as $dato => $valor)
          @if (trim((string) $valor) !== '')
          <span class="chip-dato"><small>{{ $dato }}</small>{{ $valor }}</span>
          @endif
          @endforeach
        </div>
      </div>
      <a class="btn chip-whatsapp" href="https://wa.me/{{ config('services.whatsapp') }}?text={{ rawurlencode($mensaje) }}" target="_blank" rel="noopener noreferrer">
        <img src="{{ asset('black/img/social/whatsapp.svg') }}" alt="" width="20" height="20"> Consultar por WhatsApp
      </a>
    </div>

    {{-- Comparativa de potencia --}}
    <h4 class="chip-subtitulo">Potencia y torque</h4>
    <div class="chip-comparativa">
      @foreach ($etapas as $etapa)
      @php($hp = (int) preg_replace('/\D.*/', '', (string) $etapa['potencia']))
      <div @class(['chip-etapa', 'chip-etapa-original' => $loop->first])>
        <div class="chip-etapa-nombre">{{ $etapa['nombre'] }}</div>
        <div class="chip-barra"><span style="width: {{ max(8, min(100, round($hp * 100 / $maximo))) }}%"></span></div>
        <div class="chip-etapa-valores">
          <strong>{{ $etapa['potencia'] ?? '—' }}</strong>
          <span>{{ $etapa['torque'] ?? '' }}</span>
        </div>
        <div class="chip-etapa-ganancia">
          @if ($etapa['ganancia_hp'])
          +{{ $etapa['ganancia_hp'] }} HP <small>({{ $etapa['ganancia_pct'] }}%)</small>
          @if ($etapa['ganancia_nm']) <small>· +{{ $etapa['ganancia_nm'] }} Nm</small> @endif
          @endif
        </div>
      </div>
      @endforeach
    </div>

    {{-- Detalle de cada Stage --}}
    @foreach (array_slice($etapas, 1) as $etapa)
    @if ($etapa['configuracion'] || $etapa['grafico'])
    <div class="chip-stage">
      <h4 class="chip-subtitulo">{{ $etapa['nombre'] }}</h4>
      <div class="row">
        <div @class(['col-lg-5' => $etapa['grafico'], 'col-12' => !$etapa['grafico']])>
          @if ($etapa['configuracion'])
          <p class="chip-config-titulo">Configuración necesaria</p>
          <p class="chip-config">{{ $etapa['configuracion'] }}</p>
          @endif
        </div>
        @if ($etapa['grafico'])
        <div class="col-lg-7">
          <a href="{{ $etapa['grafico'] }}" target="_blank" rel="noopener" title="Ver gráfico en grande">
            <img class="chip-grafico" src="{{ $etapa['grafico'] }}" alt="Gráfico de banco de pruebas {{ $etapa['nombre'] }}" loading="lazy">
          </a>
          <small class="chip-nota">Medición en nuestro banco de rodillos. Tocá la imagen para ampliarla.</small>
        </div>
        @endif
      </div>
    </div>
    @endif
    @endforeach

    <p class="chip-nota mt-3">Valores aproximados. Cada reprogramación se ajusta y se prueba en banco de rodillos. Trabajos garantizados.</p>
  </div>

  @else
  {{-- Marcas --}}
  <div class="chip-marcas" role="list">
    @foreach ($marcas as $m)
    <button type="button" role="listitem" wire:key="marca-{{ $m['nombre'] }}" wire:click="elegirMarca(@js($m['nombre']))"
      @class(['chip-marca', 'activa' => $slcMarca === $m['nombre']]) aria-pressed="{{ $slcMarca === $m['nombre'] ? 'true' : 'false' }}">
      @if ($m['logo'])
      <img src="{{ $m['logo'] }}" alt="" loading="lazy">
      @endif
      <span class="chip-marca-nombre">{{ $m['nombre'] }}</span>
      <span class="chip-marca-total">{{ $m['total'] }} {{ $m['total'] == 1 ? 'ficha' : 'fichas' }}</span>
    </button>
    @endforeach
  </div>

  {{-- Modelos --}}
  @if ($slcMarca !== '' || trim($buscar) !== '')
  <h4 class="chip-subtitulo">
    {{ $slcMarca !== '' ? $slcMarca : 'Resultados' }}
    <small>· {{ $modelos->count() }} {{ $modelos->count() == 1 ? 'vehículo' : 'vehículos' }}</small>
  </h4>

  @if ($modelos->isEmpty())
  <p class="chip-vacio">No encontramos ese vehículo. Escribinos y te decimos si lo podemos reprogramar.</p>
  @else
  <div class="chip-modelos">
    @foreach ($modelos as $modelo)
    @php($mejor = $modelo->mejorStage())
    <button type="button" class="chip-modelo" wire:key="modelo-{{ $modelo->id }}" wire:click="elegirModelo({{ $modelo->id }})">
      <span class="chip-modelo-nombre">@if ($slcMarca === ''){{ $modelo->marca }} @endif{{ $modelo->modelo }}</span>
      <span class="chip-modelo-detalle">{{ collect([$modelo->motorDistinto(), $modelo->anio, $modelo->combustible])->map(fn ($v) => trim((string) $v))->filter()->implode(' · ') }}</span>
      <span class="chip-modelo-potencia">
        {{ $modelo->potencia_original ?: '—' }}
        @if ($mejor && $mejor['potencia'])
        <i class="tim-icons icon-minimal-right"></i> <strong>{{ $mejor['potencia'] }}</strong>
        @if ($mejor['ganancia_pct']) <em>+{{ $mejor['ganancia_pct'] }}%</em> @endif
        @endif
      </span>
    </button>
    @endforeach
  </div>
  @endif
  @else
  <p class="chip-vacio">Elegí la marca de tu vehículo o buscalo por modelo para ver cuánta potencia gana con nuestra reprogramación.</p>
  @endif
  @endif
</div>
