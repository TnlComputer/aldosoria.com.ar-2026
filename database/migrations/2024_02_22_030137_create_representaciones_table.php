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
    Schema::create('representaciones', function (Blueprint $table) {
      $table->id();
      $table->string('representacion');
      $table->string('uri_link');
      $table->string('uri_img');
      $table->string('uri_web');
      $table->string('licencia');
      $table->string('uri_adicional');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('representaciones');
  }
};
