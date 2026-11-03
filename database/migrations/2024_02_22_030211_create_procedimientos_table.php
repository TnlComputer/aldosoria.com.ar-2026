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
    Schema::create('procedimientos', function (Blueprint $table) {
      $table->id();
      $table->string('marca');
      $table->string('modelo');
      $table->string('anio', 15);
      $table->string('motor')->nullable();
      $table->string('combutible', 10)->nullable();
      $table->string('transmicion', 10);
      $table->string('potencia_original', 10)->nullable();
      $table->string('torque_original', 10)->nullable();
      $table->string('stage1')->nullable();
      $table->string('torque_stg1', 100)->nullable();
      $table->string('stage2')->nullable();
      $table->string('torque_stg2', 100)->nullable();
      $table->string('stage3')->nullable();
      $table->string('torque_stg3', 100)->nullable();
      $table->string('configuracion')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('procedimientos');
  }
};
