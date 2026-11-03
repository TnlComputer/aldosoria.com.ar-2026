<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  /**
   * Desde 2026 el email verificado es obligatorio. Las cuentas que ya existian
   * se dan por verificadas para que sigan entrando como hasta ahora.
   */
  public function up(): void
  {
    DB::table('users')
      ->whereNull('email_verified_at')
      ->update(['email_verified_at' => DB::raw('COALESCE(created_at, CURRENT_TIMESTAMP)')]);
  }

  public function down(): void
  {
    //
  }
};
