{{-- Video de una novedad. YouTube se reproduce en la pagina; Facebook e Instagram no permiten
     mostrarse embebidos, asi que se muestra una tarjeta que abre la publicacion original. --}}
@php($url = trim((string) $novedad->uri_novedad))
@if ($origen === 'YouTube' && $url !== '')
<x-sitio.video :url="$url" :titulo="$novedad->novedades" />
@elseif ($url !== '')
<a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="novedad-externa novedad-externa-{{ strtolower($origen) }}">
  @if (in_array($origen, ['Facebook', 'Instagram', 'Twitter']))
  <img src="{{ asset('black/img/social/' . strtolower($origen) . '.svg') }}" alt="" width="40" height="40">
  @endif
  <span>Ver en {{ $origen }}</span>
</a>
@endif
