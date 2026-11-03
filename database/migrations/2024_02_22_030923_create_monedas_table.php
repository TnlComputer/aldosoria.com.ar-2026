<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('monedas', function (Blueprint $table) {
      $table->id();
      $table->string('moneda', 3)->unique();
      $table->string('simbolo', 4)->unique();
      $table->string('name', 50)->unique();
      $table->string('pais', 50)->unique();
      $table->double('cambio', 10)->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('monedas');
  }
};
