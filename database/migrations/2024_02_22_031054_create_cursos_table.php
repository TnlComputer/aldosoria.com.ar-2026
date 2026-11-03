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
    Schema::create('cursos', function (Blueprint $table) {
      $table->id();
      $table->string('name', 100);
      $table->string('title', 100);
      $table->float('videos_curso_cantidad', 3);
      $table->string('image_curso', 200);
      $table->string('video_demo_Uri');
      $table->double('coast', 10, 0);
      $table->unsignedBigInteger('modeda_id');
      $table->unsignedBigInteger('tipo_curso_id');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('cursos');
  }
};
