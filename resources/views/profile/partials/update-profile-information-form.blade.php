<section>
  <div>
    <header>
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ __('Información del Perfil') }}
      </h2>

      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Actualice la información del perfil') }}
      </p>

    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
      @csrf
    </form>

    <form method="post" action="{{ route('profile.act', $user) }}" class="mt-6 space-y-6">
      @csrf
      @method('put')
      <div>
        <x-input-label for="name" class="col-md-2" :value="__('Nombre ')" />
        <x-text-input id="name" name="name" type="text" class="block mt-1 col-md-4" value=" {{ $user->name }}" required autofocus autocomplete="name" />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
      </div>
      <div>
        <x-input-label for="last_name" class="col-md-2" :value="__('Apellido ')" />
        <x-text-input id="last_name" name="last_name" type="text" class="block mt-1 col-md-4" value="{{ $user->last_name }}" required autofocus autocomplete="last_name" />
        <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
      </div>
      <div>
        <x-input-label for="phone" class="col-md-2" :value="__('Teléfono')" />
        <x-text-input id="phone" name="phone" type="text" class="block mt-1 col-md-4" value="{{ $user->phone }}" required autofocus autocomplete="phone" />
        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
      </div>
      <div>
        <x-input-label for="pais_id" class="col-md-2" :value="__('Pais')" />
        <select name="pais_id" id="pais_id" required class="block mt-1 col-md-4">
          <option value="{{ $user->pais_id }}">{{ $paisUser->nombre }}</option>
          @foreach ($paises as $pais)
          <option value="{{$pais->id}}">{{ $pais->nombre }}</option>
          @endforeach
          </option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('pais_id')" />
      </div>

      <div>
        <x-input-label for="whatsapp" class="col-md-2" :value="__('Whatsapp')" />
        <select name="whatsapp" id="whatsapp" required class="mt-1 block col-md-4">
          <option value="{{ $user->whatsapp }}">{{ $user->whatsapp}}</option>
          <option value="NO">NO</option>
          <option value="SI">SI</option>
        </select>
        <x-input-error :messages="$errors->get('whatsapp')" class="mt-2" />
      </div>

      <div>
        <x-input-label for="tlgram" class="col-md-2" :value="__('Telegram')" />
        <select name="tlgram" id="tlgram" required class="mt-1 block col-md-4">
          <option value="{{ $user->tlgram }}">{{ $user->tlgram}}</option>
          <option value="NO">NO</option>
          <option value="SI">SI</option>
        </select>
        <x-input-error :messages="$errors->get('tlgram')" class="mt-2" />
      </div>
      <!-- <div>
        <x-input-label for="email" class="col-md-2" :value="__('Email  ')" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block col-md-4" value=" {{ $user->email }}" required autocomplete="username" />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
        <div>
          <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
            {{ __('Su dirección de correo electrónico no está verificada.') }}

            <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
              {{ __('Click here to re-send the verification email.') }}
            </button>
          </p>

          @if (session('status') === 'verification-link-sent')
          <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
            {{ __('A new verification link has been sent to your email address.') }}
          </p>
          @endif
        </div>
        @endif
      </div> -->

      <div class="flex items-center gap-4 mt-2">
        <x-primary-button>{{ __('Actualizar') }}</x-primary-button>

        @if (session('status') === 'profile-updated')
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600 dark:text-gray-400">{{ __('Actualizado.') }}</p>
        @endif
      </div>
    </form>
  </div>
</section>