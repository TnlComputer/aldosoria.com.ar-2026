<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Columnas demasiado cortas que recortaban datos ("Automática" quedaba "Automati").
   * Solo se agrandan: ningun dato existente se pierde.
   */
  public function up(): void
  {
    Schema::table('marcas_autos', function (Blueprint $table) {
      $table->string('anio', 30)->nullable()->change();
      $table->string('combustible', 30)->nullable()->change();
      $table->string('transmicion', 50)->nullable()->change();
      $table->string('potencia_original', 50)->nullable()->change();
      $table->string('torque_original', 50)->nullable()->change();
    });

    Schema::table('cursos_usuarios', function (Blueprint $table) {
      // Tiene que admitir el nombre completo del curso (cursos.name es de 100)
      $table->string('nombre_curso', 255)->change();
      $table->string('currency', 10)->nullable()->change();
      // Los importes de MercadoPago tienen centavos
      $table->decimal('amount', 12, 2)->nullable()->change();
    });

    Schema::table('cursos_videos_uris', function (Blueprint $table) {
      $table->string('archivo1_curso', 255)->nullable()->change();
      $table->string('archivo2_curso', 255)->nullable()->change();
    });

    Schema::table('monedas', function (Blueprint $table) {
      $table->string('simbolo', 10)->change();
    });
  }

  public function down(): void
  {
    // Achicar las columnas podria recortar datos: no se revierte
  }
};
