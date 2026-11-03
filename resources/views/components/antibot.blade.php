@props(['tiempo' => true])

{{-- Campo trampa: invisible para las personas, los bots lo completan --}}
<div style="position:absolute;left:-10000px;top:auto;width:1px;height:1px;overflow:hidden;" aria-hidden="true">
  <label for="{{ \App\Support\AntiBot::CAMPO_TRAMPA }}">No completar este campo</label>
  <input type="text" name="{{ \App\Support\AntiBot::CAMPO_TRAMPA }}" id="{{ \App\Support\AntiBot::CAMPO_TRAMPA }}" value="" tabindex="-1" autocomplete="off">
</div>

@if ($tiempo)
<input type="hidden" name="{{ \App\Support\AntiBot::CAMPO_TIEMPO }}" value="{{ \App\Support\AntiBot::token() }}">
@endif

@if (\App\Support\AntiBot::turnstileActivo())
<div class="cf-turnstile mt-4" data-sitekey="{{ config('services.turnstile.site_key') }}" data-language="es" data-theme="dark"></div>
@once
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endonce
@endif

@error('antibot')
<div class="mt-2 text-sm text-red-500 invalid-feedback d-block" role="alert">{{ $message }}</div>
@enderror
