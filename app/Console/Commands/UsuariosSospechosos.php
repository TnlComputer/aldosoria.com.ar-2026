<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Support\Reglas;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class UsuariosSospechosos extends Command
{
  protected $signature = 'usuarios:sospechosos
                          {--borrar : Elimina las cuentas listadas (pide confirmacion)}
                          {--dias=7 : Antiguedad minima de las cuentas sin email verificado}';

  protected $description = 'Lista (y opcionalmente borra) cuentas de spam. Nunca toca administradores ni usuarios con cursos.';

  private const VERIFICACION_DESDE = '2026-11-02';

  public function handle(): int
  {
    $sospechosos = $this->buscar((int) $this->option('dias'));

    if ($sospechosos->isEmpty()) {
      $this->info('No se encontraron cuentas sospechosas.');
      return self::SUCCESS;
    }

    $this->table(
      ['ID', 'Nombre', 'Apellido', 'Email', 'Alta', 'Motivo'],
      $sospechosos->map(fn ($s) => [
        $s['usuario']->id,
        mb_strimwidth($s['usuario']->name, 0, 30, '…'),
        mb_strimwidth((string) $s['usuario']->last_name, 0, 30, '…'),
        $s['usuario']->email,
        optional($s['usuario']->created_at)->format('d-m-Y'),
        $s['motivo'],
      ])
    );

    $this->line($sospechosos->count() . ' cuentas sospechosas (sin cursos y sin rol de administrador).');

    if (!$this->option('borrar')) {
      $this->comment('Para eliminarlas: php artisan usuarios:sospechosos --borrar');
      return self::SUCCESS;
    }

    if (!$this->confirm('¿Eliminar estas ' . $sospechosos->count() . ' cuentas? No se puede deshacer.')) {
      $this->info('No se eliminó nada.');
      return self::SUCCESS;
    }

    $borrados = 0;
    foreach ($sospechosos as $s) {
      // Doble control justo antes de borrar
      if ($s['usuario']->isAdmin() || $s['usuario']->tieneCursos()) {
        continue;
      }
      $s['usuario']->delete();
      $borrados++;
    }

    $this->info("Se eliminaron {$borrados} cuentas.");

    return self::SUCCESS;
  }

  /**
   * @return Collection<int, array{usuario: User, motivo: string}>
   */
  private function buscar(int $dias): Collection
  {
    return User::sinCursos()
      ->where(fn ($q) => $q->where('roles', '!=', 'ROLE_ADMIN')->orWhereNull('roles'))
      ->get()
      ->map(fn (User $u) => ['usuario' => $u, 'motivo' => $this->motivo($u, $dias)])
      ->filter(fn ($s) => $s['motivo'] !== null)
      ->values();
  }

  private function motivo(User $u, int $dias): ?string
  {
    $texto = $u->name . ' ' . $u->last_name . ' ' . $u->phone;

    if (preg_match('/https?:|www\.|<|>|\[url|\.(com|ru|xyz|top|online|site)\b/i', $texto)) {
      return 'enlace o código en sus datos';
    }

    if (Reglas::nombreDeBot($u->name, $u->last_name)) {
      return 'patrón de bot en nombre/apellido';
    }

    if (!preg_match("/^\p{L}[\p{L}\p{M} .'\-]*$/u", trim($u->name . ' ' . $u->last_name))) {
      return 'caracteres no válidos en el nombre';
    }

    // La verificacion de email es obligatoria solo para las cuentas creadas desde esa fecha
    if (!$u->email_verified_at && $u->created_at
      && $u->created_at->gte(self::VERIFICACION_DESDE)
      && $u->created_at->lt(now()->subDays($dias))) {
      return "no confirmó el email en {$dias} días";
    }

    return null;
  }
}
