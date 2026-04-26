<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('importaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_archivo');
            $table->datetime('fecha_importacion');
            $table->integer('registros_importados')->default(0);
            $table->integer('registros_duplicados')->default(0);
            $table->integer('registros_con_error')->default(0);
            $table->text('errores')->nullable();
            $table->string('estado')->default('completada'); // completada, parcial, fallida
            $table->string('usuario_importador')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('importaciones');
    }
};
