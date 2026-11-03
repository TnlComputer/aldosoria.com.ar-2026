<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ isset($page) ? $page . ' | ' : '' }}Soria Perfomance</title>
  <meta name="description" content="Soria Perfomance: chiptuning, reprogramación de ECU, banco de pruebas, cursos online, mecánica y electrónica automotriz en Morón, Buenos Aires.">
  <link rel="canonical" href="{{ url()->current() }}">
  @if (request()->is('dashboard*', 'profile*', 'mercadopago*', 'curso/*'))
  <meta name="robots" content="noindex, nofollow">
  @endif
  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('black') }}/img/favicon.ico">
  <link rel="icon" type="image/png" href="{{ asset('black') }}/img/favicon.ico">
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800&display=swap" rel="stylesheet" />
  <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
  <!-- Icons -->
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

  <link href="{{ asset('black') }}/css/nucleo-icons.css" rel="stylesheet" />
  <!-- CSS -->
  <link href="{{ asset('black') }}/css/black-dashboard.css?v=1.0.0" rel="stylesheet" />
  <link href="{{ asset('css/sitio.css') }}?v={{ filemtime(public_path('css/sitio.css')) }}" rel="stylesheet" />

</head>

<body class="{{ $class ?? '' }}">
  <div class="wrapper">
    @include('layouts.navbars.sidebar')
    <div class="main-panel">
      @include('layouts.navbars.navbar')

      <div class="content">
        @yield('content')
      </div>

      @include('layouts.footer')
    </div>
  </div>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
  </form>

  <script src="{{ asset('black') }}/js/core/jquery.min.js">
  </script>
  <script src="{{ asset('black') }}/js/core/popper.min.js">
  </script>
  <script src="{{ asset('black') }}/js/core/bootstrap.min.js"></script>
  <script src="{{ asset('black') }}/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <script src="{{ asset('black') }}/js/plugins/bootstrap-notify.js"></script>
  <script src="{{ asset('black') }}/js/black-dashboard.min.js?v=1.0.0"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('js/sitio.js') }}?v={{ filemtime(public_path('js/sitio.js')) }}"></script>
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-EBHNBVS5KD"></script>
  <script>
    window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'G-EBHNBVS5KD'); 
  </script>
</body>

</html>