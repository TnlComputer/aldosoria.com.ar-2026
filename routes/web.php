<?php

use App\Http\Controllers\AdminChiptuningController;
use App\Http\Controllers\AdminCursoController;
use App\Http\Controllers\AdminMarcasController;
use App\Http\Controllers\AdminMonedaController;
use App\Http\Controllers\AdminNovedadesController;
use App\Http\Controllers\AdminOfertaController;
use App\Http\Controllers\AdminPagoController;
use App\Http\Controllers\AdminTemarioController;
use App\Http\Controllers\AdminUsuarioController;
use App\Http\Controllers\ChipMarcasController;
use App\Http\Controllers\ChiptuningController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\CursoDetalleController;
use App\Http\Controllers\CursoPagoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ElectronicController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MechanicController;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\NoveltyController;
use App\Http\Controllers\PaypalCardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepresentationController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::permanentRedirect('/home', '/');
Route::get('/cursos', [CursoController::class, 'index'])->name('cursos');
Route::get('/cursos/detalle/{id}', [CursoDetalleController::class, 'show'])->whereNumber('id')->name('cursoDetalle.show');
Route::get('/mecanica', [MechanicController::class, 'index'])->name('mecanica');
Route::get('/electronica', [ElectronicController::class, 'index'])->name('electronica');
Route::get('/novedades', [NoveltyController::class, 'index'])->name('novedad');
Route::get('/chiptuning', [ChiptuningController::class, 'index'])->name('chiptuning');
Route::get('/chiptuning/marcas', [ChipMarcasController::class, 'index'])->name('chipMarcas');
Route::get('/representacion', [RepresentationController::class, 'index'])->name('representacion');
Route::get('/mantenimientos', [MaintenanceController::class, 'index'])->name('mantenimiento');
Route::get('/contactos', [ContactController::class, 'index'])->name('contacto');

Route::post('/envio_contacto', [ContactController::class, 'enviar'])->middleware('throttle:contacto')->name('contacto.enviar');

// Mercadopago: notificaciones del servidor de MercadoPago (sin sesion ni CSRF)
Route::post('/mercadopago/webhook', [MercadoPagoController::class, 'webhook'])->middleware('throttle:60,1')->name('mercadopago.payment.webhook');

Route::view('/thanks', 'pages.thanks')->name('thanks');
Route::view('/failed', 'pages.failure')->name('failure');

Route::middleware(['auth', 'verified'])->group(function () {

  Route::put('/profile/{user}', [ProfileController::class, 'act'])->name('profile.act');
  Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

  // Pagos
  Route::post('/curso/detalle', [CursoDetalleController::class, 'pago'])->name('cursoDetalle.pago');

  Route::prefix('mercadopago')->middleware('throttle:pagos')->group(function () {
    Route::post('/create_preference', [MercadoPagoController::class, 'createPreference'])->name('mercadopago.payment.createPreference');
    Route::get('/success', [MercadoPagoController::class, 'success'])->name('mercadopago.success');
    Route::get('/failure', [MercadoPagoController::class, 'failure'])->name('mercadopago.failure');
    Route::get('/pending', [MercadoPagoController::class, 'pending'])->name('mercadopago.pending');
  });

  Route::middleware('throttle:pagos')->group(function () {
    Route::post('/paypal/orden', [PaypalCardController::class, 'create'])->name('paypal.create');
    Route::post('/paypal/process', [PaypalCardController::class, 'process'])->name('paypal.process');
  });

  // Cursos comprados
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
  Route::get('/dashboard/curso/{id}', [DashboardController::class, 'show'])->whereNumber('id')->name('dashboard.show');
  Route::get('/dashboard/curso/video/{video}', [CursoPagoController::class, 'show'])->name('video.show');
  Route::get('/dashboard/curso/archivo/{temario}/{numero}', [CursoPagoController::class, 'descargar'])
    ->whereNumber(['temario', 'numero'])
    ->middleware('throttle:30,1')
    ->name('curso.archivo');

  // Administracion: solo usuarios con rol ROLE_ADMIN
  Route::middleware('admin')->group(function () {
    Route::resource('/dashboard/admin/cursos', AdminCursoController::class)->except(['show']);
    Route::resource('/dashboard/admin/cursos/temario', AdminTemarioController::class);
    Route::get('/dashboard/admin/oferta', [AdminOfertaController::class, 'index'])->name('adminOferta.index');
    Route::patch('/dashboard/admin/oferta/{oferta}', [AdminOfertaController::class, 'update'])->name('adminOferta.update');
    Route::resource('/dashboard/admin/monedas', AdminMonedaController::class)->except(['show']);
    Route::resource('/dashboard/admin/pagos', AdminPagoController::class);
    Route::resource('/dashboard/admin/usuarios', AdminUsuarioController::class)->except(['show']);
    Route::resource('/dashboard/admin/chiptuning', AdminChiptuningController::class);
    Route::resource('/dashboard/admin/novedad', AdminNovedadesController::class);
    Route::resource('/dashboard/admin/marcas', AdminMarcasController::class);
  });
});

require __DIR__ . '/auth.php';
