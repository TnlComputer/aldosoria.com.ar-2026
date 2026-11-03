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
    Schema::create('videos_cursos', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('curso_video');
      $table->unsignedBigInteger('nro_video');
      $table->string('nombre_video', 200);
      $table->integer('tipo_curso');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('videos_cursos');
  }
};
