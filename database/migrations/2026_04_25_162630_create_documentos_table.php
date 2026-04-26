<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->nullable()->constrained('personas')->nullOnDelete();

            // Datos del documento
            $table->string('nro_carpeta', 100);
            $table->string('nro_boleta', 100)->nullable();
            $table->decimal('importe_deposito', 12, 2)->default(0);
            $table->date('fecha_deposito')->nullable();
            $table->time('hora_deposito')->nullable();

            // Ubicación catastral
            $table->string('cod_manzano', 50)->nullable();
            $table->string('cod_lote', 50)->nullable();

            // Datos del contrato
            $table->string('tipo_contrato', 50)->nullable();
            $table->decimal('superficie_m2', 10, 2)->nullable();
            $table->decimal('importe_contrato', 12, 2)->nullable();
            $table->date('fecha_contrato')->nullable();
            $table->string('nro_folio', 50)->nullable();

            // Metadatos
            $table->text('observaciones')->nullable();
            $table->string('archivo_origen')->nullable();
            $table->string('hash_identidad', 64)->nullable(); // Para evitar duplicados
            $table->boolean('activo')->default(true);
            $table->text('motivo_desactivacion')->nullable();
            $table->unsignedBigInteger('importacion_id')->nullable();

            $table->timestamps();

            // Índices para búsquedas
            $table->index('nro_carpeta');
            $table->index('nro_boleta');
            $table->index('cod_manzano');
            $table->index('cod_lote');
            $table->index('activo');

            // Índice único para evitar duplicados exactos
            $table->unique(['nro_boleta', 'nro_carpeta', 'cod_manzano', 'cod_lote'], 'unique_documento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
