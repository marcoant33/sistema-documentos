<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo')->nullable();
            $table->string('ci', 50)->nullable();
            $table->string('ci_observacion')->nullable(); // Para S/D, ILEGIBLE
            $table->timestamps();

            // Índices para búsquedas rápidas
            $table->index('ci');
            $table->index('nombre_completo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
