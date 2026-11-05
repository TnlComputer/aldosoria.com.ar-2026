<?php

namespace Tests\Feature;

use App\Models\Curso;
use App\Models\CursoPago;
use App\Models\CursoUsuario;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SeguridadTest extends TestCase
{
  private User $alumno;
  private User $admin;
  private Curso $curso;

  protected function setUp(): void
  {
    parent::setUp();

    // Esquema minimo equivalente al de produccion
    Schema::create('users', function (Blueprint $t) {
      $t->id();
      $t->string('name');
      $t->string('last_name')->nullable();
      $t->string('phone')->nullable();
      $t->string('email')->unique();
      $t->timestamp('email_verified_at')->nullable();
      $t->string('password');
      $t->rememberToken();
      $t->string('whatsapp')->nullable();
      $t->string('tlgram')->nullable();
      $t->unsignedBigInteger('pais_id')->nullable();
      $t->string('roles')->nullable();
      $t->timestamps();
    });
    Schema::create('monedas', function (Blueprint $t) {
      $t->id();
      $t->string('moneda', 3);
      $t->string('simbolo')->nullable();
      $t->string('name')->nullable();
      $t->string('pais')->nullable();
      $t->double('cambio')->nullable();
      $t->timestamps();
    });
    Schema::create('cursos', function (Blueprint $t) {
      $t->id();
      $t->string('name');
      $t->string('title');
      $t->float('videos_curso_cantidad')->default(1);
      $t->string('image_curso')->default('');
      $t->string('video_demo_Uri')->default('');
      $t->double('coast');
      $t->unsignedBigInteger('moneda_id');
      $t->unsignedBigInteger('tipo_curso_id')->default(1);
      $t->timestamps();
    });
    Schema::create('cursos_videos_uris', function (Blueprint $t) {
      $t->id();
      $t->unsignedBigInteger('video_curso_id');
      $t->unsignedBigInteger('nro_video_id')->nullable();
      $t->string('nombre_curso');
      $t->string('nombre_video')->nullable();
      $t->string('descripcion');
      $t->string('uri_video')->nullable();
      $t->string('img_video')->nullable();
      $t->string('user_video')->nullable();
      $t->string('clave_video')->nullable();
      $t->integer('tipo_curso')->nullable();
      $t->string('archivo1_curso')->nullable();
      $t->string('archivo2_curso')->nullable();
      $t->timestamps();
    });
    Schema::create('cursos_pagos_videos', function (Blueprint $t) {
      $t->id();
      $t->unsignedBigInteger('user_curso_id');
      $t->unsignedBigInteger('video_curso_id');
      $t->date('vto_curso');
      $t->timestamps();
    });
    Schema::create('cursos_usuarios', function (Blueprint $t) {
      $t->id();
      $t->unsignedBigInteger('user_curso_id');
      $t->unsignedBigInteger('curso_id');
      $t->string('nombre_curso');
      $t->string('titulo_curso');
      $t->string('forma_pago_curso', 2);
      $t->date('fecha_inscripcion');
      $t->string('pago_curso', 2)->nullable();
      $t->string('payment_id')->nullable();
      $t->decimal('amount', 12, 2)->default(0);
      $t->string('currency')->nullable();
      $t->string('orderId')->nullable();
      $t->timestamps();
    });

    Schema::create('password_reset_tokens', function (Blueprint $t) {
      $t->string('email')->primary();
      $t->string('token');
      $t->timestamp('created_at')->nullable();
    });
    Schema::create('marcas_images_autos', function (Blueprint $t) {
      $t->id();
      $t->string('marca');
      $t->string('foto')->nullable();
      $t->timestamps();
    });
    Schema::create('marcas_autos', function (Blueprint $t) {
      $t->id();
      $t->string('marca');
      $t->string('modelo')->nullable();
      foreach (['anio', 'modelo_motor', 'motor', 'ecu', 'combustible', 'transmicion', 'potencia_original', 'torque_original'] as $c) {
        $t->string($c)->nullable();
      }
      foreach ([1, 2, 3] as $n) {
        $t->string('stage' . $n)->nullable();
        $t->string('torque_stg' . $n)->nullable();
        $t->string('img_st' . $n)->nullable();
        $t->text('configuracion_stg' . $n)->nullable();
      }
      $t->timestamps();
    });
    Schema::create('novedades', function (Blueprint $t) {
      $t->id();
      $t->string('novedades');
      $t->date('fecha');
      $t->string('uri_novedad');
      $t->string('tipo_novedad', 3)->nullable();
      $t->string('foto_novedad')->nullable();
      $t->timestamps();
    });
    Schema::create('ofertas', function (Blueprint $t) {
      $t->id();
      $t->string('oferta')->nullable();
      $t->timestamps();
    });
    Schema::create('pais', function (Blueprint $t) {
      $t->id();
      $t->string('nombre')->nullable();
      $t->string('codigo_2', 2)->nullable();
    });
    \DB::table('pais')->insert([
      ['id' => 1, 'nombre' => 'Argentina', 'codigo_2' => 'AR'],
      ['id' => 2, 'nombre' => 'Chile', 'codigo_2' => 'CL'],
      ['id' => \App\Models\Pais::SIN_PAIS, 'nombre' => 'Seleccione un pais', 'codigo_2' => null],
    ]);

    \DB::table('monedas')->insert(['id' => 2, 'moneda' => 'EUR', 'simbolo' => '€', 'cambio' => 1000]);

    $this->alumno = $this->usuario('alumno@example.com', 'ROLE_USER');
    $this->admin = $this->usuario('admin@example.com', 'ROLE_ADMIN');
    $this->curso = Curso::create(['name' => 'ECU', 'title' => 'Stage 1', 'videos_curso_cantidad' => 1, 'image_curso' => 'a.jpg', 'video_demo_Uri' => '', 'coast' => 50, 'moneda_id' => 2, 'tipo_curso_id' => 1]);

    config([
      'services.mercadopago.access_token' => 'TEST-token',
      'services.mercadopago.webhook_secret' => null,
      'services.paypal.client_id' => 'id',
      'services.paypal.client_secret' => 'secret',
    ]);

    Mail::fake();
  }

  private function usuario(string $email, string $rol): User
  {
    $user = new User(['name' => 'N', 'last_name' => 'A', 'phone' => '1', 'email' => $email, 'password' => 'secreto123']);
    $user->roles = $rol;
    $user->email_verified_at = now();
    $user->save();

    return $user;
  }

  private function pagoMercadoPago(array $cambios = []): array
  {
    return array_merge([
      'id' => 'ORD01TEST123456',
      'status' => 'processed',
      'status_detail' => 'accredited',
      'external_reference' => 'u' . $this->alumno->id . '-c' . $this->curso->id . '-abcdefghij',
      'total_amount' => '50000.00',
      'total_paid_amount' => '50000.00',
      'currency' => 'ARS',
      'transactions' => ['payments' => [['id' => 'PAY01TEST123456', 'status' => 'processed', 'status_detail' => 'accredited']]],
    ], $cambios);
  }

  public function test_un_alumno_no_puede_entrar_a_la_administracion(): void
  {
    $this->actingAs($this->alumno)->get('/dashboard/admin/pagos')->assertForbidden();
    $this->actingAs($this->alumno)->post('/dashboard/admin/pagos', [
      'user_curso_id' => $this->alumno->id,
      'video_curso_id' => $this->curso->id,
      'vto_curso' => 365,
    ])->assertForbidden();

    $this->assertSame(0, CursoPago::count());
  }

  public function test_un_admin_puede_dar_de_alta_un_pago(): void
  {
    $this->actingAs($this->admin)->post('/dashboard/admin/pagos', [
      'user_curso_id' => $this->alumno->id,
      'video_curso_id' => $this->curso->id,
      'vto_curso' => 30,
      'forma_pago_curso' => 2,
    ])->assertRedirect()->assertSessionHasNoErrors();

    $this->assertSame(1, CursoPago::count());
  }

  /** Datos de un registro legitimo, con la marca de tiempo del formulario */
  private function registro(array $cambios = []): array
  {
    $datos = array_merge([
      'name' => 'María José', 'last_name' => "O'Connor-Peña", 'phone' => '+54 9 11 4444-5555',
      'email' => 'nuevo@example.com', 'password' => 'clave12345', 'password_confirmation' => 'clave12345',
      'pais_id' => 1, 'whatsapp' => 'SI', 'tlgram' => 'NO',
      \App\Support\AntiBot::CAMPO_TIEMPO => \App\Support\AntiBot::token(),
    ], $cambios);

    $this->travel(5)->seconds();

    return $datos;
  }

  public function test_el_registro_no_permite_asignarse_administrador(): void
  {
    $this->post('/register', $this->registro(['roles' => 'ROLE_ADMIN']))->assertRedirect();

    $user = User::where('email', 'nuevo@example.com')->first();
    $this->assertNotNull($user, 'El registro legitimo debe funcionar');
    $this->assertSame('ROLE_USER', $user->roles);
    $this->assertNull($user->email_verified_at);
  }

  public function test_emails_en_mayusculas_son_validos(): void
  {
    $this->post('/register', $this->registro(['email' => 'INFO@ALDOSORIA.COM.AR']))->assertSessionHasNoErrors();
    $this->assertSame('info@aldosoria.com.ar', User::where('email', 'info@aldosoria.com.ar')->value('email'));
    auth()->logout();

    $jorge = $this->usuario('tnlcomputer@gmail.com', 'ROLE_ADMIN');
    $this->post('/login', ['email' => 'TNLCOMPUTER@GMAIL.COM', 'password' => 'secreto123'])->assertSessionHasNoErrors();
    $this->assertAuthenticatedAs($jorge);
  }

  public function test_el_registro_frena_bots(): void
  {
    $casos = [
      'campo trampa' => $this->registro([\App\Support\AntiBot::CAMPO_TRAMPA => 'http://spam.ru']),
      'sin marca de tiempo' => array_diff_key($this->registro(), [\App\Support\AntiBot::CAMPO_TIEMPO => 1]),
      'patron nombre+2 letras' => $this->registro(['name' => 'MichaelDet', 'last_name' => 'MichaelDetTH']),
      'mayusculas al azar' => $this->registro(['name' => 'ySkDjmWUEyZrTQmYgQ', 'last_name' => 'wxrYkPZynYQnpqMHlEL']),
      'enlace en el nombre' => $this->registro(['name' => 'Gana plata www.spam.com']),
      'codigo en el nombre' => $this->registro(['name' => '<script>alert(1)</script>']),
      'telefono con texto' => $this->registro(['phone' => 'llamame http://x.ru']),
    ];

    foreach ($casos as $caso => $datos) {
      $this->post('/register', $datos);
      $this->assertSame(0, User::where('email', 'nuevo@example.com')->count(), "No debe registrarse: $caso");
      auth()->logout();
    }
  }

  public function test_el_registro_rechaza_envios_instantaneos(): void
  {
    $datos = $this->registro();
    $datos[\App\Support\AntiBot::CAMPO_TIEMPO] = \App\Support\AntiBot::token();

    $this->post('/register', $datos)->assertSessionHasErrors('antibot');
    $this->assertSame(0, User::where('email', 'nuevo@example.com')->count());
  }

  public function test_el_login_con_campo_trampa_se_rechaza(): void
  {
    $this->post('/login', ['email' => 'alumno@example.com', 'password' => 'secreto123', \App\Support\AntiBot::CAMPO_TRAMPA => 'x'])
      ->assertSessionHasErrors('antibot');
    $this->assertGuest();

    $this->post('/login', ['email' => 'alumno@example.com', 'password' => 'secreto123'])->assertRedirect();
    $this->assertAuthenticatedAs($this->alumno);
  }

  public function test_recuperar_clave_no_revela_si_el_email_existe(): void
  {
    Mail::fake();
    \Illuminate\Support\Facades\Notification::fake();

    $datos = [\App\Support\AntiBot::CAMPO_TIEMPO => \App\Support\AntiBot::token()];
    $this->travel(5)->seconds();

    $this->post('/forgot-password', $datos + ['email' => 'noexiste@example.com'])
      ->assertSessionHasNoErrors()->assertSessionHas('status');
    $this->post('/forgot-password', $datos + ['email' => 'alumno@example.com'])
      ->assertSessionHasNoErrors()->assertSessionHas('status');
  }

  public function test_el_perfil_no_acepta_spam(): void
  {
    $this->actingAs($this->alumno)->put('/profile/' . $this->alumno->id, [
      'name' => 'Visitá http://casino.ru', 'last_name' => 'A', 'phone' => '1144445555', 'pais_id' => 1,
    ])->assertSessionHasErrors('name');

    $this->assertSame('N', $this->alumno->fresh()->name);
  }

  public function test_no_se_puede_borrar_un_usuario_con_cursos(): void
  {
    $comprador = $this->usuario('comprador@example.com', 'ROLE_USER');
    CursoPago::create(['user_curso_id' => $comprador->id, 'video_curso_id' => $this->curso->id, 'vto_curso' => now()->subYear()]);

    $this->actingAs($this->admin)->delete('/dashboard/admin/usuarios/' . $comprador->id)->assertSessionHas('error');
    $this->assertNotNull($comprador->fresh());

    $sinCursos = $this->usuario('nada@example.com', 'ROLE_USER');
    $this->actingAs($this->admin)->delete('/dashboard/admin/usuarios/' . $sinCursos->id);
    $this->assertNull($sinCursos->fresh());
  }

  public function test_la_limpieza_de_spam_nunca_borra_compradores_ni_admins(): void
  {
    $botComprador = $this->usuario('bot1@example.com', 'ROLE_USER');
    $botComprador->forceFill(['name' => 'MichaelDet', 'last_name' => 'MichaelDetTH'])->save();
    CursoUsuario::create(['user_curso_id' => $botComprador->id, 'curso_id' => $this->curso->id, 'nombre_curso' => 'ECU', 'titulo_curso' => 'S1', 'forma_pago_curso' => 3, 'fecha_inscripcion' => now()]);

    $botAdmin = $this->usuario('bot2@example.com', 'ROLE_ADMIN');
    $botAdmin->forceFill(['name' => 'IsmaelHep', 'last_name' => 'IsmaelHepOQ'])->save();

    $bot = $this->usuario('bot3@example.com', 'ROLE_USER');
    $bot->forceFill(['name' => 'AndrewLaw', 'last_name' => 'AndrewLawUW'])->save();

    $this->artisan('usuarios:sospechosos', ['--borrar' => true])
      ->expectsConfirmation('¿Eliminar estas 1 cuentas? No se puede deshacer.', 'yes')
      ->assertSuccessful();

    $this->assertNull($bot->fresh());
    $this->assertNotNull($botComprador->fresh());
    $this->assertNotNull($botAdmin->fresh());
    $this->assertNotNull($this->alumno->fresh());
  }

  public function test_no_se_pueden_ver_cursos_no_comprados(): void
  {
    $this->actingAs($this->alumno)->get('/dashboard/curso/video/' . $this->curso->id)->assertForbidden();

    CursoPago::create(['user_curso_id' => $this->alumno->id, 'video_curso_id' => $this->curso->id, 'vto_curso' => now()->addDays(15)]);

    $this->actingAs($this->alumno)->get('/dashboard/curso/video/' . $this->curso->id)->assertOk();
  }

  public function test_un_curso_vencido_no_se_puede_ver(): void
  {
    CursoPago::create(['user_curso_id' => $this->alumno->id, 'video_curso_id' => $this->curso->id, 'vto_curso' => now()->subDay()]);

    $this->actingAs($this->alumno)->get('/dashboard/curso/video/' . $this->curso->id)->assertForbidden();
    $this->actingAs($this->admin)->get('/dashboard/curso/video/' . $this->curso->id)->assertOk();
  }

  public function test_los_archivos_de_cursos_no_son_publicos_y_se_descargan_con_permiso(): void
  {
    $this->assertFileDoesNotExist(public_path('archivos'));

    $temario = \App\Models\CursoVideoUri::create(['video_curso_id' => $this->curso->id, 'nombre_curso' => 'ECU', 'descripcion' => 'x', 'tipo_curso' => 2, 'archivo1_curso' => 'prueba_test.rar']);
    \Storage::disk('local')->put('cursos/prueba_test.rar', 'contenido');

    $url = '/dashboard/curso/archivo/' . $temario->id . '/1';
    $this->actingAs($this->alumno)->get($url)->assertForbidden();

    CursoPago::create(['user_curso_id' => $this->alumno->id, 'video_curso_id' => $this->curso->id, 'vto_curso' => now()->addDays(15)]);
    $this->actingAs($this->alumno)->get($url)->assertOk();

    \Storage::disk('local')->delete('cursos/prueba_test.rar');
  }

  public function test_mercadopago_success_falso_no_habilita_el_curso(): void
  {
    Http::fake(['api.mercadopago.com/*' => Http::response(['message' => 'not found'], 404)]);

    $this->actingAs($this->alumno)
      ->get('/mercadopago/success?order_id=ORD01FALSO&external_reference=' . $this->curso->id)
      ->assertViewIs('pages.failure');

    $this->actingAs($this->alumno)
      ->get('/mercadopago/success?order_id=../../v1/payments/1')
      ->assertViewIs('pages.failure');

    $this->assertSame(0, CursoPago::count());
  }

  public function test_mercadopago_pago_aprobado_habilita_el_curso_una_sola_vez(): void
  {
    Http::fake(['api.mercadopago.com/v1/orders/ORD01TEST123456' => Http::response($this->pagoMercadoPago())]);

    $this->actingAs($this->alumno)->get('/mercadopago/success?order_id=ORD01TEST123456')->assertViewIs('pages.thanks');
    $this->postJson('/mercadopago/webhook', ['type' => 'order', 'action' => 'order.processed', 'data' => ['id' => 'ORD01TEST123456']])->assertOk();

    $this->assertSame(1, CursoPago::count());
    $this->assertSame(1, CursoUsuario::count());
  }

  public function test_mercadopago_crea_la_order_con_el_precio_del_servidor_y_la_verifica_al_volver(): void
  {
    Http::fake([
      'api.mercadopago.com/v1/orders' => Http::response(['id' => 'ORD01TEST123456', 'status' => 'created', 'checkout_url' => 'https://www.mercadopago.com.ar/checkout/v1/redirect?order_id=ORD01TEST123456'], 201),
      'api.mercadopago.com/v1/orders/ORD01TEST123456' => Http::response($this->pagoMercadoPago()),
    ]);

    $this->actingAs($this->alumno)
      ->postJson('/mercadopago/create_order', ['curso_id' => $this->curso->id, 'precio' => 1])
      ->assertOk()
      ->assertJson(['checkout_url' => 'https://www.mercadopago.com.ar/checkout/v1/redirect?order_id=ORD01TEST123456']);

    Http::assertSent(function ($request) {
      return $request->url() === 'https://api.mercadopago.com/v1/orders'
        && $request->hasHeader('X-Idempotency-Key')
        && $request['total_amount'] === '50000.00'
        && $request['items'][0]['unit_price'] === '50000.00'
        && str_starts_with($request['external_reference'], 'u' . $this->alumno->id . '-c' . $this->curso->id . '-');
    });

    // MercadoPago puede volver sin order_id: se usa la order guardada en la sesion
    $this->actingAs($this->alumno)->get('/mercadopago/success')->assertViewIs('pages.thanks');
    $this->assertSame(1, CursoPago::count());
  }

  public function test_la_pagina_de_pago_de_mercadopago_usa_el_boton_de_redireccion(): void
  {
    $this->actingAs($this->alumno)->post('/curso/detalle', ['curso_id' => $this->curso->id, 'metodoPago' => 'mp'])
      ->assertOk()
      ->assertViewIs('pages.pagoMercadoPago')
      ->assertSee('mercadopago-pagar', false)
      ->assertSee('create_order', false)
      ->assertDontSee('sdk.mercadopago.com', false);
  }

  public function test_mercadopago_order_pendiente_muestra_pendiente(): void
  {
    Http::fake(['api.mercadopago.com/*' => Http::response($this->pagoMercadoPago(['status' => 'action_required', 'status_detail' => 'waiting_payment', 'total_paid_amount' => '0.00']))]);

    $this->actingAs($this->alumno)->get('/mercadopago/success?order_id=ORD01TEST123456')->assertViewIs('pages.pending');
    $this->assertSame(0, CursoPago::count());
  }

  public function test_mercadopago_rechaza_importe_menor(): void
  {
    Http::fake(['api.mercadopago.com/*' => Http::response($this->pagoMercadoPago(['total_paid_amount' => '1.00']))]);

    $this->actingAs($this->alumno)->get('/mercadopago/success?order_id=ORD01TEST123456')->assertViewIs('pages.failure');
    $this->assertSame(0, CursoPago::count());
  }

  public function test_mercadopago_no_acredita_pagos_de_otro_usuario(): void
  {
    Http::fake(['api.mercadopago.com/*' => Http::response($this->pagoMercadoPago(['external_reference' => 'u' . $this->admin->id . '-c' . $this->curso->id . '-x']))]);

    $this->actingAs($this->alumno)->get('/mercadopago/success?order_id=ORD01TEST123456')->assertViewIs('pages.failure');
    $this->assertSame(0, CursoPago::where('user_curso_id', $this->alumno->id)->count());
  }

  public function test_mercadopago_webhook_valida_la_firma_si_hay_clave(): void
  {
    config(['services.mercadopago.webhook_secret' => 'clave']);
    Http::fake(['api.mercadopago.com/*' => Http::response($this->pagoMercadoPago())]);

    $this->postJson('/mercadopago/webhook', ['type' => 'order', 'data' => ['id' => 'ORD01TEST123456']], ['x-signature' => 'ts=1,v1=malo'])
      ->assertStatus(401);

    $ts = '1700000000';
    $firma = hash_hmac('sha256', 'id:ord01test123456;request-id:req-1;ts:' . $ts . ';', 'clave');
    $this->postJson('/mercadopago/webhook?data.id=ORD01TEST123456&type=order', ['type' => 'order', 'data' => ['id' => 'ORD01TEST123456']], ['x-signature' => "ts=$ts,v1=$firma", 'x-request-id' => 'req-1'])
      ->assertOk();

    $this->assertSame(1, CursoPago::count());
  }

  public function test_paypal_no_acepta_datos_inventados_por_el_navegador(): void
  {
    // El ataque anterior: enviar datos falsos sin pagar
    $this->actingAs($this->alumno)->postJson('/paypal/process', [
      'orderID' => 'FAKE',
      'payerID' => 'x',
      'datos' => ['curso_id' => $this->curso->id, 'user_id' => $this->alumno->id, 'unit_price' => 0],
    ])->assertStatus(422);

    $this->assertSame(0, CursoPago::count());
  }

  public function test_paypal_rechaza_orden_con_importe_menor(): void
  {
    Http::fake([
      '*/v1/oauth2/token' => Http::response(['access_token' => 't']),
      '*/v2/checkout/orders/*' => Http::response($this->ordenPayPal('1.00')),
    ]);

    $this->actingAs($this->alumno)->postJson('/paypal/process', ['orderID' => 'ABC12345', 'curso_id' => $this->curso->id])->assertStatus(422);
    $this->assertSame(0, CursoPago::count());
  }

  public function test_paypal_orden_completa_habilita_el_curso(): void
  {
    Http::fake([
      '*/v1/oauth2/token' => Http::response(['access_token' => 't']),
      '*/v2/checkout/orders/*' => Http::response($this->ordenPayPal('50.00')),
    ]);

    $this->actingAs($this->alumno)->postJson('/paypal/process', ['orderID' => 'ABC12345', 'curso_id' => $this->curso->id])->assertOk();
    $this->assertSame(1, CursoPago::count());
  }

  private function ordenPayPal(string $importe): array
  {
    return [
      'id' => 'ABC12345',
      'status' => 'COMPLETED',
      'purchase_units' => [[
        'custom_id' => 'u' . $this->alumno->id . '-c' . $this->curso->id,
        'payments' => ['captures' => [[
          'id' => 'CAP1', 'status' => 'COMPLETED',
          'amount' => ['currency_code' => 'EUR', 'value' => $importe],
        ]]],
      ]],
    ];
  }

  public function test_la_preferencia_usa_el_precio_de_la_base(): void
  {
    $this->actingAs($this->alumno)->post('/curso/detalle', [
      'curso_id' => $this->curso->id,
      'metodoPago' => 'pa',
      'coast' => 0.01,
    ])->assertOk()->assertViewHas('datos', fn ($d) => $d['unit_price'] === '50.00' && $d['price'] == 50000);
  }

  public function test_no_se_pueden_subir_scripts_como_imagen(): void
  {
    $shell = UploadedFile::fake()->createWithContent('foto.php', '<?php echo 1; ?>');

    $this->actingAs($this->admin)->post('/dashboard/admin/marcas', ['marca' => 'X', 'imagen' => $shell])
      ->assertSessionHasErrors('imagen');

    $this->assertEmpty(glob(public_path('black/img/Marcas/*.php')));
  }

  public function test_el_formulario_de_contacto_funciona_y_tiene_limite(): void
  {
    $datos = ['txtNombre' => 'Juan', 'txtTelefono' => '1144445555', 'txtEmail' => 'juan@example.com', 'txtAsunto' => 'Consulta', 'txtMensaje' => 'Hola, quiero info',
      \App\Support\AntiBot::CAMPO_TIEMPO => \App\Support\AntiBot::token()];
    $this->travel(5)->seconds();

    $this->post('/envio_contacto', $datos)->assertRedirect()->assertSessionHas('success');

    for ($i = 0; $i < 3; $i++) {
      $respuesta = $this->post('/envio_contacto', $datos);
    }
    $respuesta->assertStatus(429);
  }

  public function test_el_contacto_rechaza_codigo_y_spam_de_enlaces(): void
  {
    $base = ['txtNombre' => 'Juan', 'txtTelefono' => '1144445555', 'txtEmail' => 'juan@example.com', 'txtAsunto' => 'Consulta',
      \App\Support\AntiBot::CAMPO_TIEMPO => \App\Support\AntiBot::token()];
    $this->travel(5)->seconds();

    $this->post('/envio_contacto', $base + ['txtMensaje' => '<script>alert(1)</script>'])->assertSessionHasErrors('txtMensaje');
    $this->post('/envio_contacto', $base + ['txtMensaje' => 'http://a.ru http://b.ru http://c.ru casino'])->assertSessionHasErrors('txtMensaje');
    $this->post('/envio_contacto', $base + ['txtMensaje' => 'Hola'] + [\App\Support\AntiBot::CAMPO_TRAMPA => 'x'])->assertSessionHasErrors('antibot');
  }

  public function test_buscador_de_usuarios_del_admin(): void
  {
    $juan = $this->usuario('juan.perez@example.com', 'ROLE_USER');
    $juan->forceFill(['name' => 'Juan', 'last_name' => 'Perez', 'pais_id' => 1, 'phone' => '+54 9 11 5555-1234', 'whatsapp' => 'SI'])->save();
    CursoPago::create(['user_curso_id' => $juan->id, 'video_curso_id' => $this->curso->id, 'vto_curso' => now()->addDays(15)]);

    $this->actingAs($this->admin)->get('/dashboard/admin/usuarios?q=juan+perez')
      ->assertOk()
      ->assertSee('juan.perez@example.com')
      ->assertDontSee('alumno@example.com')
      ->assertSee('Argentina')
      ->assertSee('https://wa.me/5491155551234', false);

    $this->actingAs($this->admin)->get('/dashboard/admin/usuarios?cursos=sin')
      ->assertOk()->assertSee('alumno@example.com')->assertDontSee('juan.perez@example.com');

    $this->actingAs($this->admin)->get('/dashboard/admin/usuarios?rol=ROLE_ADMIN')
      ->assertOk()->assertSee('admin@example.com')->assertDontSee('alumno@example.com');

    // Los comodines de LIKE no deben traer todo
    $this->actingAs($this->admin)->get('/dashboard/admin/usuarios?q=%25')->assertOk()->assertDontSee('alumno@example.com');

    $this->actingAs($this->alumno)->get('/dashboard/admin/usuarios?q=a')->assertForbidden();
  }

  public function test_todas_las_pantallas_del_admin_funcionan(): void
  {
    $tema = \App\Models\CursoVideoUri::create(['video_curso_id' => $this->curso->id, 'nombre_curso' => 'ECU', 'descripcion' => 'Clase 1', 'tipo_curso' => 1]);
    $compra = CursoUsuario::create(['user_curso_id' => $this->alumno->id, 'curso_id' => $this->curso->id, 'nombre_curso' => 'ECU', 'titulo_curso' => 'S1', 'forma_pago_curso' => 3, 'fecha_inscripcion' => now(), 'pago_curso' => 'P', 'amount' => 50]);
    CursoPago::create(['user_curso_id' => $this->alumno->id, 'video_curso_id' => $this->curso->id, 'vto_curso' => now()->addDays(5)]);
    $marca = \App\Models\Marcas_images_autos::create(['marca' => 'Audi', 'foto' => 'audi.png']);
    $chip = \App\Models\Marcas_autos::create(['marca' => 'Audi', 'modelo' => 'A3', 'motor' => '1.8T', 'stage1' => '210 HP']);
    $novedad = \App\Models\Novedad::create(['novedades' => 'Nueva', 'fecha' => now(), 'uri_novedad' => 'https://youtube.com/embed/x', 'tipo_novedad' => 4, 'foto_novedad' => 1]);
    \App\Models\Oferta::create(['oferta' => 'oferta.jpg']);

    $paginas = [
      '/dashboard', '/dashboard/admin/cursos', '/dashboard/admin/cursos?q=ECU', '/dashboard/admin/cursos/create',
      '/dashboard/admin/cursos/' . $this->curso->id . '/edit', '/dashboard/admin/cursos/temario/' . $this->curso->id,
      '/dashboard/admin/cursos/temario/create?curso=' . $this->curso->id, '/dashboard/admin/cursos/temario/' . $tema->id . '/edit',
      '/dashboard/admin/pagos', '/dashboard/admin/pagos?q=alumno&acceso=vigente&forma=3', '/dashboard/admin/pagos/create',
      '/dashboard/admin/pagos/' . $this->alumno->id, '/dashboard/admin/pagos/' . $compra->id . '/edit',
      '/dashboard/admin/usuarios', '/dashboard/admin/usuarios/create', '/dashboard/admin/usuarios/' . $this->alumno->id . '/edit',
      '/dashboard/admin/chiptuning', '/dashboard/admin/chiptuning?q=a3&marca=Audi', '/dashboard/admin/chiptuning/create', '/dashboard/admin/chiptuning/' . $chip->id . '/edit',
      '/dashboard/admin/marcas', '/dashboard/admin/marcas/create', '/dashboard/admin/marcas/' . $marca->id . '/edit',
      '/dashboard/admin/novedad', '/dashboard/admin/novedad/create', '/dashboard/admin/novedad/' . $novedad->id . '/edit',
      '/dashboard/admin/monedas', '/dashboard/admin/monedas/create', '/dashboard/admin/monedas/2/edit',
      '/dashboard/admin/oferta',
    ];

    foreach ($paginas as $url) {
      $this->actingAs($this->admin)->get($url)->assertOk();
    }

    // El listado de pagos muestra la compra con su acceso vigente
    $this->actingAs($this->admin)->get('/dashboard/admin/pagos?acceso=vigente')->assertSee('alumno@example.com')->assertSee('Hasta');
    $this->actingAs($this->admin)->get('/dashboard/admin/pagos?acceso=vencido')->assertDontSee('alumno@example.com');
    $this->actingAs($this->admin)->get('/dashboard/admin/chiptuning?q=zzz')->assertDontSee('1.8T');
  }

  public function test_no_se_borran_cursos_monedas_ni_marcas_en_uso(): void
  {
    CursoPago::create(['user_curso_id' => $this->alumno->id, 'video_curso_id' => $this->curso->id, 'vto_curso' => now()]);
    $marca = \App\Models\Marcas_images_autos::create(['marca' => 'Audi', 'foto' => 'audi.png']);
    \App\Models\Marcas_autos::create(['marca' => 'Audi', 'modelo' => 'A3']);

    $this->actingAs($this->admin)->delete('/dashboard/admin/cursos/' . $this->curso->id)->assertSessionHas('error');
    $this->actingAs($this->admin)->delete('/dashboard/admin/monedas/2')->assertSessionHas('error');
    $this->actingAs($this->admin)->delete('/dashboard/admin/marcas/' . $marca->id)->assertSessionHas('error');

    $this->assertNotNull($this->curso->fresh());
    $this->assertNotNull($marca->fresh());
  }

  public function test_chiptuning_calcula_la_ganancia_y_muestra_la_ficha(): void
  {
    \App\Models\Marcas_images_autos::create(['marca' => 'Audi', 'foto' => 'audi.png']);
    $chip = \App\Models\Marcas_autos::create(['marca' => 'Audi', 'modelo' => 'A3 8P 1.4T', 'motor' => '1.4 TFSI',
      'potencia_original' => '122-125 HP', 'torque_original' => '200 NM', 'stage1' => '150 HP', 'torque_stg1' => '250 NM', 'stage2' => '', 'stage3' => '']);

    $etapas = $chip->etapas();
    $this->assertCount(2, $etapas);
    $this->assertSame(28, $etapas[1]['ganancia_hp']);
    $this->assertSame(23, $etapas[1]['ganancia_pct']);
    $this->assertSame(50, $etapas[1]['ganancia_nm']);

    $this->get('/chiptuning')->assertOk()->assertSee('Audi')->assertSee('1 ficha');
    $this->get('/chiptuning/marcas?marca=Audi')->assertOk()->assertSee('A3 8P 1.4T')->assertSee('+23%');
    $this->get('/chiptuning/marcas?marca=Audi&ficha=' . $chip->id)->assertOk()->assertSee('+28 HP')->assertSee('wa.me/', false);
    $this->get('/chiptuning/marcas?q=1.4')->assertOk()->assertSee('A3 8P 1.4T');
  }

  public function test_las_fichas_de_chiptuning_se_guardan_prolijas(): void
  {
    $n = fn (array $d) => \App\Support\FichaChip::normalizar($d);

    $this->assertSame('Automática', $n(['transmicion' => 'Automati'])['transmicion']);
    $this->assertSame('Diesel', $n(['combustible' => 'Gas Oil '])['combustible']);
    $this->assertSame('180 HP', $n(['stage1' => '180HP Aprox'])['stage1']);
    $this->assertSame('115-120 HP', $n(['potencia_original' => '115 - 120 HP'])['potencia_original']);
    $this->assertSame('275 Nm', $n(['torque_original' => '275 HP'])['torque_original']);
    $this->assertSame('', $n(['torque_original' => '0 NM'])['torque_original']);
    $this->assertSame('Filtro Aire Cónico, K&N, Downpipe', $n(['configuracion_stg1' => 'Filtro Aire CÃ³nico ,KN , Dowpipe'])['configuracion_stg1']);
    // Los nombres de archivo no se tocan
    $this->assertSame('Audi A 3  1998.jpg', $n(['img_st1' => 'Audi A 3  1998.jpg '])['img_st1']);

    \App\Models\Marcas_images_autos::create(['marca' => 'Audi', 'foto' => 'audi.png']);
    $chip = \App\Models\Marcas_autos::create(['marca' => 'Audi', 'modelo' => 'A5', 'modelo_motor' => 'EA888']);

    $this->actingAs($this->admin)->put('/dashboard/admin/chiptuning/' . $chip->id, [
      'marca' => 'Audi', 'modelo' => ' A5  8F ', 'motor' => '2.0 TSI', 'modelo_motor' => 'EA888',
      'transmicion' => 'automatica', 'combustible' => 'nafta', 'potencia_original' => '224HP', 'torque_original' => '320 NM',
      'stage1' => '265hp', 'torque_stg1' => '376nm',
    ])->assertRedirect()->assertSessionHasNoErrors();

    $chip->refresh();
    $this->assertSame('A5 8F', $chip->modelo);
    $this->assertSame('EA888', $chip->modelo_motor);
    $this->assertSame('Automática', $chip->transmicion);
    $this->assertSame('Gasolina', $chip->combustible);
    $this->assertSame('224 HP', $chip->potencia_original);
    $this->assertSame('376 Nm', $chip->torque_stg1);
  }

  public function test_un_stage_con_solo_descripcion_se_muestra(): void
  {
    $chip = \App\Models\Marcas_autos::create(['marca' => 'Mercedes', 'modelo' => 'Sprinter', 'potencia_original' => '140 HP', 'configuracion_stg1' => 'Limitador de velocidad liberado']);

    $etapas = $chip->etapas();
    $this->assertCount(2, $etapas);
    $this->assertSame('Limitador de velocidad liberado', $etapas[1]['configuracion']);
  }

  public function test_las_paginas_publicas_funcionan(): void
  {
    \App\Models\Novedad::create(['novedades' => 'Video nuevo', 'fecha' => now(), 'uri_novedad' => 'https://www.youtube.com/embed/abcdefghijk', 'tipo_novedad' => 4, 'foto_novedad' => 1]);

    foreach (['/', '/mecanica', '/electronica', '/representacion', '/mantenimientos', '/novedades', '/cursos', '/contactos', '/chiptuning', '/chiptuning/marcas'] as $url) {
      $this->get($url)->assertOk();
    }

    $this->get('/home')->assertRedirect('/');
    // Los videos se cargan livianos: miniatura y reproductor recien al tocar play
    $this->get('/')->assertSee('i.ytimg.com/vi/abcdefghijk', false)->assertSee('ECU')->assertSee('Video nuevo');
  }

  public function test_nadie_ve_un_curso_que_no_compro(): void
  {
    $otroCurso = Curso::create(['name' => 'WinOLS Pro', 'title' => 'Avanzado', 'videos_curso_cantidad' => 1, 'image_curso' => 'b.jpg', 'video_demo_Uri' => '', 'coast' => 300, 'moneda_id' => 2, 'tipo_curso_id' => 2]);
    $videoA = \App\Models\CursoVideoUri::create(['video_curso_id' => $this->curso->id, 'nombre_curso' => 'ECU', 'descripcion' => 'Clase A', 'tipo_curso' => 1, 'uri_video' => 'https://player.vimeo.com/video/111111111']);
    $videoB = \App\Models\CursoVideoUri::create(['video_curso_id' => $otroCurso->id, 'nombre_curso' => 'WinOLS Pro', 'descripcion' => 'Clase B', 'tipo_curso' => 1, 'uri_video' => 'https://player.vimeo.com/video/222222222']);
    $archivoB = \App\Models\CursoVideoUri::create(['video_curso_id' => $otroCurso->id, 'nombre_curso' => 'WinOLS Pro', 'descripcion' => 'Archivos B', 'tipo_curso' => 2, 'archivo1_curso' => 'secreto_b.rar']);
    \Storage::disk('local')->put('cursos/secreto_b.rar', 'contenido pago');

    // El alumno compro solo el curso A
    $pagoA = CursoPago::create(['user_curso_id' => $this->alumno->id, 'video_curso_id' => $this->curso->id, 'vto_curso' => now()->addDays(10)]);
    // Otro alumno compro el B: eso no le da acceso al primero
    $otro = $this->usuario('otro@example.com', 'ROLE_USER');
    CursoPago::create(['user_curso_id' => $otro->id, 'video_curso_id' => $otroCurso->id, 'vto_curso' => now()->addDays(10)]);

    // Sin sesion: todo pide login
    $this->get('/dashboard/curso/video/' . $otroCurso->id)->assertRedirect('/login');
    $this->get('/dashboard/curso/archivo/' . $archivoB->id . '/1')->assertRedirect('/login');

    // Con sesion: ve su curso y nada del otro
    $this->actingAs($this->alumno)->get('/dashboard/curso/video/' . $this->curso->id)->assertOk()->assertSee('111111111')->assertDontSee('222222222');
    $this->actingAs($this->alumno)->get('/dashboard/curso/video/' . $otroCurso->id)->assertForbidden();
    $this->actingAs($this->alumno)->get('/dashboard/curso/archivo/' . $archivoB->id . '/1')->assertForbidden();
    $this->actingAs($this->alumno)->get('/dashboard/curso/archivo/' . $archivoB->id . '/3')->assertForbidden();
    $this->actingAs($this->alumno)->get('/dashboard/curso/' . $otroCurso->id)->assertRedirect();
    $this->actingAs($this->alumno)->get('/dashboard')->assertSee('ECU')->assertDontSee('WinOLS Pro');

    // La pagina publica muestra el temario, pero nunca los videos ni los archivos
    $this->get('/cursos/detalle/' . $otroCurso->id)->assertOk()->assertSee('Clase B')
      ->assertDontSee('222222222')->assertDontSee('vimeo')->assertDontSee('secreto_b');

    // Vencido: deja de verlo
    $pagoA->update(['vto_curso' => now()->subDay()]);
    $this->actingAs($this->alumno)->get('/dashboard/curso/video/' . $this->curso->id)->assertForbidden();

    // El dueño del curso B si lo ve; el admin ve todo
    $this->actingAs($otro)->get('/dashboard/curso/archivo/' . $archivoB->id . '/1')->assertOk();
    $this->actingAs($this->admin)->get('/dashboard/curso/video/' . $otroCurso->id)->assertOk();

    \Storage::disk('local')->delete('cursos/secreto_b.rar');
  }

  public function test_los_archivos_de_cursos_se_guardan_en_privado(): void
  {
    $bin = UploadedFile::fake()->createWithContent('Mapa EDC17.bin', str_repeat("\x00\xFF", 512));

    $this->actingAs($this->admin)->post('/dashboard/admin/cursos/temario', [
      'id_curso' => $this->curso->id, 'nro_video_id' => 1, 'descripcion' => 'Archivo Bin', 'tipo_curso' => 2, 'archivo1_curso' => $bin,
    ])->assertRedirect()->assertSessionHasNoErrors();

    $tema = \App\Models\CursoVideoUri::where('descripcion', 'Archivo Bin')->firstOrFail();
    $nombre = $tema->archivo1_curso;

    // Nombre generado por el servidor, guardado fuera de la carpeta publica
    $this->assertMatchesRegularExpression('/^\d{14}_1_[a-z0-9]{6}\.bin$/', $nombre);
    $this->assertFileExists(storage_path('app/cursos/' . $nombre));
    $this->assertFileDoesNotExist(public_path('archivos/' . $nombre));
    $this->assertEmpty(glob(public_path('**/' . $nombre)));

    // Solo el comprador lo descarga
    $url = '/dashboard/curso/archivo/' . $tema->id . '/1';
    $this->actingAs($this->admin)->get($url)->assertOk();
    $this->app['auth']->forgetGuards();
    $this->get($url)->assertRedirect('/login');
    $this->actingAs($this->alumno)->get($url)->assertForbidden();
    CursoPago::create(['user_curso_id' => $this->alumno->id, 'video_curso_id' => $this->curso->id, 'vto_curso' => now()->addDays(5)]);
    $this->actingAs($this->alumno)->get($url)->assertOk()->assertDownload();

    // Un script nunca se acepta como archivo de curso
    $php = UploadedFile::fake()->createWithContent('curso.php', '<?php echo 1; ?>');
    $this->actingAs($this->admin)->post('/dashboard/admin/cursos/temario', [
      'id_curso' => $this->curso->id, 'descripcion' => 'Malo', 'tipo_curso' => 2, 'archivo1_curso' => $php,
    ])->assertSessionHasErrors('archivo1_curso');

    @unlink(storage_path('app/cursos/' . $nombre));
  }

  public function test_un_archivo_demasiado_grande_muestra_un_aviso(): void
  {
    $this->actingAs($this->admin)
      ->from('/dashboard/admin/cursos/temario/create')
      ->call('POST', '/dashboard/admin/cursos/temario', [], [], [], ['CONTENT_LENGTH' => 5 * 1024 * 1024 * 1024])
      ->assertRedirect('/dashboard/admin/cursos/temario/create')
      ->assertSessionHas('error');
  }

  public function test_cabeceras_de_seguridad(): void
  {
    $this->get('/thanks')
      ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
      ->assertHeader('X-Content-Type-Options', 'nosniff');
  }

  public function test_paginas_de_cursos_muestran_el_estado_de_compra(): void
  {
    \App\Models\CursoVideoUri::create(['video_curso_id' => $this->curso->id, 'nombre_curso' => 'ECU', 'descripcion' => 'Clase uno', 'tipo_curso' => 1, 'uri_video' => 'https://player.vimeo.com/video/333333333']);
    \App\Models\CursoVideoUri::create(['video_curso_id' => $this->curso->id, 'nombre_curso' => 'ECU', 'descripcion' => 'Archivos de trabajo', 'tipo_curso' => 2, 'archivo1_curso' => 'trabajo.rar']);
    $detalle = '/cursos/detalle/' . $this->curso->id;

    // Visitante: ve temario, precio, acceso y como crear cuenta
    $this->get($detalle)->assertOk()
      ->assertSee('Clase uno')->assertSee('1 clase en video')->assertSee('1 entrega con archivos')
      ->assertSee(\App\Services\InscripcionCursoService::DIAS_ACCESO . ' días de acceso')
      ->assertSee('Crear cuenta')->assertDontSee('Comprar curso')
      ->assertDontSee('333333333')->assertDontSee('trabajo.rar');
    $this->get('/cursos')->assertOk()->assertSee('2 clases')->assertDontSee('Ya lo tenés');

    // Alumno sin el curso: puede comprarlo
    $this->actingAs($this->alumno)->get($detalle)->assertSee('Comprar curso')->assertDontSee('Ya tenés este curso');

    // Alumno con el curso vigente: se le ofrece entrar, no comprarlo de nuevo
    CursoPago::create(['user_curso_id' => $this->alumno->id, 'video_curso_id' => $this->curso->id, 'vto_curso' => now()->addDays(5)]);
    $this->actingAs($this->alumno)->get($detalle)->assertSee('Ya tenés este curso')->assertSee('Ir a Mis cursos')->assertDontSee('Comprar curso');
    $this->actingAs($this->alumno)->get('/cursos')->assertSee('Ya lo tenés')->assertSee('Ir al curso');
  }

  public function test_novedades_y_contacto(): void
  {
    \App\Models\Novedad::create(['novedades' => 'Video viejo de Facebook', 'fecha' => now()->subYear(), 'uri_novedad' => ' https://www.facebook.com/mecatronicaaldo.soria/videos/1', 'tipo_novedad' => 1, 'foto_novedad' => 2]);
    \App\Models\Novedad::create(['novedades' => 'Stage 2 en el banco', 'fecha' => now(), 'uri_novedad' => 'https://www.youtube.com/embed/abcdefghijk', 'tipo_novedad' => 4, 'foto_novedad' => 1]);

    // La mas reciente va destacada; Facebook no se embebe (no lo permite), se enlaza
    $this->get('/novedades')->assertOk()
      ->assertSeeInOrder(['Lo último', 'Stage 2 en el banco', 'Más videos', 'Video viejo de Facebook'])
      ->assertSee('href="https://www.facebook.com/mecatronicaaldo.soria/videos/1"', false)
      ->assertDontSee('<iframe src="https://www.facebook.com', false);

    // El asunto puede venir precargado, sin HTML
    $this->get('/contactos?asunto=' . urlencode('<b>Turno para el taller</b>'))->assertOk()
      ->assertSee('value="Turno para el taller"', false)->assertDontSee('<b>Turno', false);
  }

  public function test_seo_sitemap_fichas_y_datos_estructurados(): void
  {
    $ficha = \App\Models\Marcas_autos::create(['marca' => 'Volkswagen', 'modelo' => 'Golf GTI', 'motor' => '2.0 TSI', 'potencia_original' => '230 HP', 'stage1' => '300 HP']);

    // Sitemap con paginas, cursos y cada ficha
    $this->get('/sitemap.xml')->assertOk()
      ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
      ->assertSee(route('cursoDetalle.show', $this->curso->id), false)
      ->assertSee(e(route('chipMarcas', ['ficha' => $ficha->id])), false)
      ->assertSee(e(route('chipMarcas', ['marca' => 'Volkswagen'])), false);

    // Cada ficha es una pagina propia para Google
    $this->get('/chiptuning/marcas?ficha=' . $ficha->id)->assertOk()
      ->assertSee('<title>Chiptuning Volkswagen Golf GTI 2.0 TSI | Soria Perfomance</title>', false)
      ->assertSee('<link rel="canonical" href="' . e(route('chipMarcas', ['ficha' => $ficha->id])) . '">', false)
      ->assertSee('de 230 HP a 300 HP en Stage 1');
    // Las busquedas libres no generan paginas duplicadas
    $this->get('/chiptuning/marcas?q=golf')->assertSee('<link rel="canonical" href="' . route('chipMarcas') . '">', false);

    // Negocio local en el inicio y curso con precio en su detalle
    $inicio = $this->get('/')->assertOk()->assertSee('property="og:image"', false)->getContent();
    preg_match('~<script type="application/ld\+json">(.*?)</script>~s', $inicio, $m);
    $this->assertSame('AutoRepair', json_decode($m[1], true)['@type']);
    $detalle = $this->get('/cursos/detalle/' . $this->curso->id)->getContent();
    preg_match('~<script type="application/ld\+json">(.*?)</script>~s', $detalle, $m);
    $curso = json_decode($m[1], true);
    $this->assertSame('Course', $curso['@type']);
    $this->assertSame((string) $this->curso->coast, $curso['offers']['price']);

    // Lo privado no se indexa
    $this->actingAs($this->alumno)->get('/dashboard')->assertSee('noindex, nofollow', false);
    $this->get('/cursos')->assertSee('index, follow', false);
  }

  public function test_mis_cursos_muestra_estado_vacio_vigente_y_vencido(): void
  {
    // Sin cursos: mensaje claro (antes la pantalla quedaba vacia)
    $this->actingAs($this->alumno)->get('/dashboard')->assertOk()
      ->assertSee('Todavía no tenés cursos')->assertSee('Ver cursos disponibles');

    // Vigente: entra al curso
    $pago = CursoPago::create(['user_curso_id' => $this->alumno->id, 'video_curso_id' => $this->curso->id, 'vto_curso' => now()->addDays(10)]);
    $this->actingAs($this->alumno)->get('/dashboard')
      ->assertSee('Entrar al curso')->assertSee('Disponible')->assertDontSee('Todavía no tenés cursos');

    // Por vencer y vencido
    $pago->update(['vto_curso' => now()->addDays(2)]);
    $this->actingAs($this->alumno)->get('/dashboard')->assertSee('Vence en 2 días');
    $pago->update(['vto_curso' => now()->subDays(3)]);
    $this->actingAs($this->alumno)->get('/dashboard')
      ->assertSee('Vencido')->assertSee('Renovar acceso')->assertDontSee('Entrar al curso');
  }

  public function test_el_registro_exige_un_telefono_del_pais_elegido(): void
  {
    // "Seleccione un pais" no es un pais
    $this->post('/register', $this->registro(['pais_id' => \App\Models\Pais::SIN_PAIS]))->assertSessionHasErrors('pais_id');

    // Numero de Chile con Argentina elegida
    $this->post('/register', $this->registro(['phone' => '+56 9 8970 0668']))
      ->assertSessionHasErrors(['phone' => 'Ese teléfono es de Chile, pero elegiste Argentina. Corregí el país o el número.']);

    // Numero inventado
    $this->post('/register', $this->registro(['phone' => '123456']))->assertSessionHasErrors('phone');
    $this->assertSame(0, User::where('email', 'nuevo@example.com')->count());

    // Correcto: se guarda en formato internacional
    $this->post('/register', $this->registro(['phone' => '11 5824-5077']))->assertSessionHasNoErrors();
    $this->assertSame('+54 11 5824-5077', User::where('email', 'nuevo@example.com')->value('phone'));
    auth()->logout();

    // El mismo telefono escrito de otra forma no puede registrarse de nuevo
    $this->post('/register', $this->registro(['email' => 'otro@example.com', 'phone' => '+54 11 5824 5077']))
      ->assertSessionHasErrors('phone');

    // Con Chile elegido, el numero chileno es valido
    $this->post('/register', $this->registro(['email' => 'chile@example.com', 'pais_id' => 2, 'phone' => '+56 9 8970 0668']))
      ->assertSessionHasNoErrors();
  }

  public function test_el_formulario_de_registro_lista_paises_sin_la_opcion_de_relleno(): void
  {
    $this->get('/register')->assertOk()
      ->assertSee('Más frecuentes')->assertSee('>Argentina</option>', false)
      ->assertDontSee('Seleccione un pais');
  }

  public function test_turnstile_activo_frena_bots_y_deja_pasar_personas(): void
  {
    config(['services.turnstile.site_key' => '0x4AAAAprueba', 'services.turnstile.secret' => 'secreto-prueba']);

    // El widget aparece en los formularios
    $this->get('/register')->assertSee('class="cf-turnstile', false)->assertSee('challenges.cloudflare.com/turnstile', false);
    $this->get('/contactos')->assertSee('data-sitekey="0x4AAAAprueba"', false);

    // Primera consulta a Cloudflare: no es una persona; segunda: si
    \Illuminate\Support\Facades\Http::fake(['challenges.cloudflare.com/*' => \Illuminate\Support\Facades\Http::sequence()
      ->push(['success' => false])
      ->push(['success' => true])]);

    // Cloudflare dice que no es una persona: se rechaza
    $this->post('/register', $this->registro(['cf-turnstile-response' => 'token-falso']))->assertSessionHasErrors('antibot');
    $this->assertSame(0, User::where('email', 'nuevo@example.com')->count());

    // Cloudflare la verifica: se registra
    $this->post('/register', $this->registro(['cf-turnstile-response' => 'token-bueno']))->assertSessionHasNoErrors();
    $this->assertSame(1, User::where('email', 'nuevo@example.com')->count());
    \Illuminate\Support\Facades\Http::assertSent(fn ($r) => $r['secret'] === 'secreto-prueba' && $r['response'] === 'token-bueno');
  }
}
