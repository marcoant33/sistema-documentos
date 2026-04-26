<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Importacion extends Model
{
    use HasFactory;

    // 👇 Esta línea es la solución
    protected $table = 'importaciones';

    protected $fillable = [
        'nombre_archivo',
        'fecha_importacion',
        'registros_importados',
        'registros_duplicados',
        'registros_con_error',
        'errores',
        'estado',
        'usuario_importador'
    ];

    // ... el resto del código
}
