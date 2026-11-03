<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Confirmación de Pago</title>
</head>

<body>
  <h2>¡Gracias por tu compra!</h2>

  <p>Has realizado un pago de <strong>{{ $datos['moneda'] ?? '' }} {{ number_format((float) ($datos['monto'] ?? 0), 2, ',', '.') }}</strong>.</p>

  @if(isset($datos['curso']))
  <p>Curso adquirido: <strong>{{ $datos['curso'] }}</strong></p>
  @endif

  @if(isset($datos['fecha']))
  <p>Fecha: <strong>{{ $datos['fecha'] }}</strong></p>
  @endif

  <p>Email del comprador: <strong>{{ $datos['cliente_email'] ?? 'No disponible' }}</strong></p>

  <p>Ya podés ver el curso desde tu panel en <a href="{{ route('dashboard') }}">{{ route('dashboard') }}</a>.</p>

  <p>Si tenés alguna duda, escribinos a <strong>{{ config('mail.admin_address') }}</strong></p>

  <br>
  <p>Saludos,<br>El equipo de la Academia</p>
</body>

</html>
