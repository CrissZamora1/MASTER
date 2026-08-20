<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Garantia extends Model
{
    protected $fillable = [
        'nombre', 'meses_duracion', 'descripcion',
    ];

    // Se activa cuando exista el modelo Reclamo (siguiente módulo)
    public function reclamos()
    {
        return $this->hasMany(Reclamo::class);
    }
}