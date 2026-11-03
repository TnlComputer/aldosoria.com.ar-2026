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
    Schema::create('napro_act', function (Blueprint $table) {
      $table->id();
      $table->string('img1', 150);
      $table->string('version', 3);
      $table->string('img2', 150)->nullable();
      $table->string('im3', 150)->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('napro_act');
  }
};
