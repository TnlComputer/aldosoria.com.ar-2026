@if (session('success'))
<div class="alert alert-success admin-alerta" role="status">{{ session('success') }}</div>
@endif
@if (session('danger'))
<div class="alert alert-warning admin-alerta" role="status">{{ session('danger') }}</div>
@endif
@if (session('error'))
<div class="alert alert-danger admin-alerta" role="alert">{{ session('error') }}</div>
@endif
@if ($errors->any())
<div class="alert alert-danger admin-alerta" role="alert">
  <strong>Revisá los datos:</strong>
  <ul class="mb-0 pl-3">
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif
