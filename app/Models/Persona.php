<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'personas';

    protected $fillable = [
        'nombre_completo',
        'ci',
        'ci_observacion'
    ];

    // Relación con documentos
    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

    // Scope para buscar por CI
    public function scopePorCI($query, $ci)
    {
        return $query->where('ci', $ci);
    }

    // Accesor para mostrar CI limpio
    public function getCILimpioAttribute()
    {
        if ($this->ci_observacion) {
            return $this->ci_observacion;
        }
        return $this->ci ?: 'No registrado';
    }
}
