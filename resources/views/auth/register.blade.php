@php($selectClases = 'block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm')

<x-guest-layout>
  <form method="POST" action="{{ route('register') }}">
    @csrf
    <x-antibot />

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div>
        <x-input-label for="name" :value="__('Nombre')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus
          autocomplete="given-name" maxlength="50" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
      </div>
      <div>
        <x-input-label for="last_name" :value="__('Apellido')" />
        <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required
          autocomplete="family-name" maxlength="50" />
        <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
      <div>
        <x-input-label for="phone" :value="__('Teléfono')" />
        <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required
          autocomplete="tel" maxlength="30" placeholder="Elegí primero tu país" aria-describedby="phone-ayuda" />
        <p id="phone-ayuda" class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tiene que ser un número del país que elegís.</p>
        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
      </div>
      <div>
        <x-input-label for="pais_id" :value="__('País')" />
        <select name="pais_id" id="pais_id" required class="{{ $selectClases }}" autocomplete="country">
          <x-opciones-pais :frecuentes="$frecuentes" :resto="$resto" :seleccionado="old('pais_id')" />
        </select>
        <x-input-error :messages="$errors->get('pais_id')" class="mt-2" />
      </div>
    </div>

    <div class="mt-4">
      <x-input-label for="email" :value="__('Email')" />
      <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
        autocomplete="email" maxlength="150" />
      <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
      <div>
        <x-input-label for="password" :value="__('Password')" />
        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
      </div>
      <div>
        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required
          autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
      </div>
    </div>
    <p class="mt-1 text-xs text-gray-400">Mínimo 8 caracteres, con letras y números.</p>

    <div class="grid grid-cols-2 gap-4 mt-4">
      <div>
        <x-input-label for="whatsapp" :value="__('¿Usás WhatsApp en ese teléfono?')" />
        <select name="whatsapp" id="whatsapp" class="{{ $selectClases }}">
          <option value="SI" @selected(old('whatsapp', 'SI') === 'SI')>Sí</option>
          <option value="NO" @selected(old('whatsapp') === 'NO')>No</option>
        </select>
      </div>
      <div>
        <x-input-label for="tlgram" :value="__('¿Y Telegram?')" />
        <select name="tlgram" id="tlgram" class="{{ $selectClases }}">
          <option value="NO" @selected(old('tlgram', 'NO') === 'NO')>No</option>
          <option value="SI" @selected(old('tlgram') === 'SI')>Sí</option>
        </select>
      </div>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-3 mt-6">
      <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md" href="{{ route('login') }}">
        {{ __('Ya estas registrado?') }}
      </a>

      <x-primary-button class="w-full sm:w-auto justify-center">
        {{ __('Registrarse') }}
      </x-primary-button>
    </div>
  </form>

  {{-- Muestra un ejemplo de telefono del pais elegido --}}
  <script>
    (function () {
      var ejemplos = @json($ejemplos);
      var pais = document.getElementById('pais_id');
      var tel = document.getElementById('phone');
      var ayuda = document.getElementById('phone-ayuda');
      function actualizar() {
        var ej = ejemplos[pais.value];
        tel.placeholder = ej || 'Elegí primero tu país';
        ayuda.textContent = ej ? 'Número de ese país, por ejemplo: ' + ej : 'Tiene que ser un número del país que elegís.';
      }
      pais.addEventListener('change', actualizar);
      actualizar();
    })();
  </script>

</x-guest-layout>
