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
    Schema::create('cursos_usuarios', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user_curso_id');
      // $table->foreign('user_curso_id')->references('id')->on('users');

      $table->unsignedBigInteger('curso_id');
      $table->string('nombre_curso', 50);
      $table->string('titulo_curso', 200);
      $table->string('forma_pago_curso', 2);
      $table->date('fecha_inscripcion');
      $table->string('pago_curso', 2)->nullable();
      $table->string('payment_id')->nullable();
      $table->integer('amount');
      $table->string('currency')->nullable();
      $table->string('orderId')->nullable();

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('cursos_usuarios');
  }
};
