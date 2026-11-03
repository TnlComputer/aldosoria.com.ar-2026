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
        Schema::create('novedades', function (Blueprint $table) {
            $table->id();
            $table->string('novedades');
            $table->date('fecha');
            $table->string('uri_novedad');
            $table->string('tipo_novedad', 3)->nullable();
            $table->string('foto_novedad')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('novedades');
    }
};
