@extends('layouts.app', ['page' => __('PayPal'), 'pageSlug' => 'cursos'])

@section('content')

<div class="card-body m-sm-none m-md-5">
  <div class="chart-area">
    <div class="col-12 align-content-center">
      <div class="text-center">
        <h1 class="">¡ Paso final !</h1>
        <hr class="m-2">
        <p class=""> Estás a punto de pagar con Paypal la cantidad de:

        <h4 class="importe">€ {{ $datos['unit_price'] }}</h4>
        </p>
        <div class="col-6 m-auto">
          <div id="paypal-button-container" class="align-center w-100"></div>
          <p>Los productos podrán ser vistos una vez que se procese el pago<br>
            <strong>(Para aclaraciones: aldosoria@gmail.com)</strong>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://www.paypal.com/sdk/js?client-id={{ urlencode(config('services.paypal.client_id')) }}&currency=EUR">
</script>
<script>
  (function () {
    const cursoId = @json($datos['curso_id']);
    const headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': @json(csrf_token()),
    };

    const enviar = (url, body) => fetch(url, { method: 'POST', headers, body: JSON.stringify(body) })
      .then((response) => {
        if (!response.ok) {
          throw new Error('Error ' + response.status);
        }
        return response.json();
      });

    // La orden se crea y se captura en el servidor, con el importe del curso guardado en la base
    paypal.Buttons({
      fundingSource: paypal.FUNDING.PAYPAL,
      createOrder: function () {
        return enviar(@json(route('paypal.create')), { curso_id: cursoId }).then((orden) => orden.id);
      },
      onApprove: function (data) {
        return enviar(@json(route('paypal.process')), { orderID: data.orderID, curso_id: cursoId })
          .then(() => { window.location.href = @json(route('thanks')); })
          .catch(() => { window.location.href = @json(route('failure')); });
      },
      onError: function () {
        window.location.href = @json(route('failure'));
      }
    }).render('#paypal-button-container');
  })();
</script>

@endsection
