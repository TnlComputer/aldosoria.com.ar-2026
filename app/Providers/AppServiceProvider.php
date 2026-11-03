<?php

namespace App\Providers;

use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Paginator::useBootstrap();

    // Las peticiones de Livewire conservan el control de rol de la pagina de origen
    Livewire::addPersistentMiddleware([EnsureUserIsAdmin::class]);

    if ($this->app->environment('production')) {
      URL::forceScheme('https');
    }

    Password::defaults(fn () => Password::min(8)->letters()->numbers());

    VerifyEmail::toMailUsing(fn ($usuario, string $url) => (new MailMessage)
      ->subject('Confirmá tu email - Soria Perfomance')
      ->greeting('¡Hola ' . $usuario->name . '!')
      ->line('Gracias por registrarte. Para activar tu cuenta, confirmá tu email con el botón de abajo.')
      ->action('Confirmar email', $url)
      ->line('Si no creaste esta cuenta, ignorá este mensaje.')
      ->salutation('Saludos, Soria Perfomance'));

    ResetPassword::toMailUsing(fn ($usuario, string $token) => (new MailMessage)
      ->subject('Restablecer contraseña - Soria Perfomance')
      ->greeting('¡Hola ' . $usuario->name . '!')
      ->line('Recibimos un pedido para restablecer la contraseña de tu cuenta.')
      ->action('Elegir nueva contraseña', url(route('password.reset', ['token' => $token, 'email' => $usuario->getEmailForPasswordReset()], false)))
      ->line('El enlace vence en ' . config('auth.passwords.users.expire') . ' minutos.')
      ->line('Si no lo pediste, ignorá este mensaje: tu contraseña no cambia.')
      ->salutation('Saludos, Soria Perfomance'));
  }
}
