<?php

return [

  /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

  'mailgun' => [
    'domain' => env('MAILGUN_DOMAIN'),
    'secret' => env('MAILGUN_SECRET'),
    'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    'scheme' => 'https',
  ],

  'postmark' => [
    'token' => env('POSTMARK_TOKEN'),
  ],

  'ses' => [
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
  ],

  'mercadopago' => [
    'base_url' => env('MERCADOPAGO_BASE_URL', 'https://api.mercadopago.com'),
    'access_token' => env('MERCADOPAGO_ACCESS_TOKEN'),
    'public_key' => env('MERCADOPAGO_PUBLIC_KEY'),
    // Clave secreta de webhooks (Tus integraciones > Webhooks) para validar x-signature
    'webhook_secret' => env('MERCADOPAGO_WEBHOOK_SECRET'),
  ],

  // Numero de WhatsApp del taller (formato internacional, solo digitos)
  'whatsapp' => env('WHATSAPP_NUMERO', '5491127154269'),

  // Cloudflare Turnstile (captcha gratuito). Si no hay claves, el sitio funciona sin el.
  'turnstile' => [
    'site_key' => env('TURNSTILE_SITE_KEY'),
    'secret' => env('TURNSTILE_SECRET_KEY'),
  ],

  'paypal' => [
    'client_id' => env('PAYPAL_CLIENT_ID'),
    'client_secret' => env('PAYPAL_CLIENT_SECRET', env('PAYPAL_SECRET')),
    // live | sandbox
    'mode' => env('PAYPAL_MODE', 'live'),
  ],

];
