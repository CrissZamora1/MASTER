<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $fillable = [
        'cita_id', 'casa_id', 'cliente_id',
        'fecha_hora_entrega', 'resultado', 'observaciones',
    ];

    protected $casts = [
        'fecha_hora_entrega' => 'datetime',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function casa()
    {
        return $this->belongsTo(Casa::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Se activa cuando exista el modelo ReporteEntrega (siguiente módulo)
    public function reportesEntrega()
    {
        return $this->hasMany(ReporteEntrega::class);
    }
}