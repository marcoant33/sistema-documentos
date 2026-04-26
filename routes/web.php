<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\ImportacionController;
use App\Http\Controllers\ReporteController;

Route::get('/', [ReporteController::class, 'dashboard'])->name('dashboard');

// Documentos (solo lectura)
Route::get('/documentos', [DocumentoController::class, 'index'])->name('documentos.index');

// Importaciones
Route::get('/importaciones', [ImportacionController::class, 'index'])->name('importaciones.index');
Route::post('/importaciones/procesar', [ImportacionController::class, 'importar'])->name('importaciones.procesar');

// Reportes
Route::get('/reportes', [ReporteController::class, 'reportesIndex'])->name('reportes.index');
