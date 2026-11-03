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
    Schema::create('cursos_videos_uris', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('video_curso_id');
      $table->unsignedBigInteger('nro_video_id')->nullable();
      $table->string('nombre_curso', 100);
      $table->string('nombre_video')->nullable();
      $table->string('descripcion');
      $table->string('uri_video')->nullable();
      $table->string('img_video')->nullable();
      $table->string('user_video', 100)->nullable();
      $table->string('clave_video', 100)->nullable();
      $table->integer('tipo_curso')->nullable();
      $table->string('archivo1_curso', 50)->nullable();
      $table->string('archivo2_curso', 50)->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('cursos_videos_uris');
  }
};
