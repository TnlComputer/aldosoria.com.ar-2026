<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('usuarios', function (Blueprint $table) {
      $table->id();
      // $table->string('email', 100)->unique();
      // $table->string('password');
      // $table->string('name');
      // $table->string('last_name');
      // $table->unsignedBigInteger('ciudad_id')->nullable();
      // $table->unsignedBigInteger('pais_id')->nullable();
      // $table->string('whatsapp', 20)->nullable();
      // $table->string('tlgram', 20)->nullable();
      // $table->string('phone', 20);
      // $table->string('role', 2);
      $table->string('nick', 50);
      $table->string('nombre', 100);
      $table->string('clave');
      $table->date('ingreso');
      $table->string('permiso', 2);

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('usuarios');
  }
};
