<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Ubicacion de la aplicacion
|--------------------------------------------------------------------------
|
| En el hosting la aplicacion vive en "base/", al lado de public_html.
| En desarrollo se usa la estructura estandar de Laravel.
|
*/

$base = is_file(__DIR__ . '/../base/vendor/autoload.php')
    ? __DIR__ . '/../base'
    : __DIR__ . '/..';

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
*/

if (file_exists($maintenance = $base . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
*/

require $base . '/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
*/

$app = require_once $base . '/bootstrap/app.php';

// La carpeta publica es siempre la de este archivo (public_html en el hosting)
$app->usePublicPath(__DIR__);

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
