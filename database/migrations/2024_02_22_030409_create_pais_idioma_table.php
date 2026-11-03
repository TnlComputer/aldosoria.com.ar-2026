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
    Schema::create('pais_idioma', function (Blueprint $table) {
      $table->id();
      $table->string('idioma', 30);
      $table->string('oficial', 1);
      $table->float('porcentaje');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('pais_idioma');
  }
};
