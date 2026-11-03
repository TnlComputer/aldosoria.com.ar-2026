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
    Schema::create('marcas_chip', function (Blueprint $table) {
      $table->id();
      $table->string('marca');
      $table->string('modelo');
      $table->string('anio', 15)->nullable();
      $table->string('modelo_motor')->nullable();
      $table->string('motor')->nullable();
      $table->string('ecu')->nullable();
      $table->string('combutible', 10)->nullable();
      $table->string('transmicion', 10)->nullable();
      $table->string('potencia_original', 10)->nullable();
      $table->string('torque_original', 10)->nullable();
      $table->string('stage1')->nullable();
      $table->string('torque_stg1', 100)->nullable();
      $table->string('img_st1')->nullable();
      $table->string('stage2')->nullable();
      $table->string('torque_stg2', 100)->nullable();
      $table->string('img_st2')->nullable();
      $table->string('stage3')->nullable();
      $table->string('torque_stg3', 100)->nullable();
      $table->string('img_st3')->nullable();
      $table->string('configuracion_stg1')->nullable();
      $table->string('configuracion_stg2')->nullable();
      $table->string('configuracion_stg3')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('marcas_chip');
  }
};
