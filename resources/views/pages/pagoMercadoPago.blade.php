@extends('layouts.app', ['page' => __('Mercado Pago'), 'pageSlug' => 'cursos'])

@section('content')
<div class="card-body m-sm-none m-md-5">
  <div class="chart-area">
    <div class="col-12 align-content-center">
      <div class="text-center">
        <h1 class="">¡Paso final!</h1>
        <hr class="m-2">
        <p>Estás a punto de pagar con Mercado Pago la cantidad de:</p>
        <h4 class="importe">{{ $datos['description_name'] }}</h4>
        <h4 class="importe">{{ $datos['description_title'] }}</h4>
        <h4 class="importe">$ {{ number_format($datos['price'], 2, ',', '.') }}</h4>
        <div class="col-8 m-auto">
          <button type="button" id="mercadopago-pagar" class="btn btn-primary btn-lg btn-block my-3">Pagar con Mercado Pago</button>
          <p id="mercadopago-error" class="text-danger" role="alert" hidden>No se pudo iniciar el pago. Probá de nuevo en unos minutos.</p>
          <p>Te vamos a llevar a Mercado Pago para completar el pago. El video podrá ser visto una vez que se procese.</p>
          <h5><strong>Cualquier duda, enviar un email a: aldosoria@gmail.com</strong></h5>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function () {
    const boton = document.getElementById('mercadopago-pagar');
    const error = document.getElementById('mercadopago-error');
    const textoBoton = boton.textContent;

    boton.addEventListener('click', function () {
      boton.disabled = true;
      boton.textContent = 'Conectando con Mercado Pago…';
      error.hidden = true;

      // El importe lo calcula el servidor a partir del curso
      fetch(@json(route('mercadopago.payment.createOrder')), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': @json(csrf_token()),
        },
        body: JSON.stringify({ curso_id: @json($datos['curso_id']) }),
      })
        .then(function (response) {
          if (!response.ok) {
            throw new Error('Error ' + response.status);
          }
          return response.json();
        })
        .then(function (respuesta) {
          window.location.href = respuesta.checkout_url;
        })
        .catch(function () {
          boton.disabled = false;
          boton.textContent = textoBoton;
          error.hidden = false;
        });
    });
  })();
</script>
@endsection
