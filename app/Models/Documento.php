<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documentos';

    protected $fillable = [
        'persona_id', 'nro_carpeta', 'nro_boleta', 'importe_deposito',
        'fecha_deposito', 'hora_deposito', 'cod_manzano', 'cod_lote',
        'tipo_contrato', 'superficie_m2', 'importe_contrato', 'fecha_contrato',
        'nro_folio', 'observaciones', 'archivo_origen', 'hash_identidad',
        'activo', 'motivo_desactivacion', 'importacion_id'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'importe_deposito' => 'decimal:2',
        'importe_contrato' => 'decimal:2',
        'superficie_m2' => 'decimal:2',
        'fecha_deposito' => 'date',
        'fecha_contrato' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relaciones
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function importacion()
    {
        return $this->belongsTo(Importacion::class);
    }

    // Scope para filtrar solo activos
    public function scopeActivo(Builder $query)
    {
        return $query->where('activo', true);
    }

    // Calcular diferencia entre contrato y depósito
    public function getDiferenciaAttribute()
    {
        return ($this->importe_contrato ?? 0) - $this->importe_deposito;
    }

    // Calcular valor por metro cuadrado
    public function getValorPorM2Attribute()
    {
        if ($this->superficie_m2 && $this->superficie_m2 > 0) {
            return ($this->importe_contrato ?? 0) / $this->superficie_m2;
        }
        return 0;
    }

    // ⚠️ BLOQUEAR MODIFICACIONES DESDE WEB
    protected static function boot()
    {
        parent::boot();

        static::updating(function($model) {
            if (app()->runningInConsole() === false) {
                throw new \Exception('❌ Los documentos NO pueden modificarse desde la interfaz web. Solo se permiten inserciones.');
            }
        });

        static::deleting(function($model) {
            if (app()->runningInConsole() === false) {
                throw new \Exception('❌ No se pueden eliminar documentos desde la interfaz web.');
            }
        });
    }
}
