<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ReporteEntrega extends Model
{
    protected $fillable = [
        'entrega_id', 'descripcion', 'foto', 'estado', 'encargado',
    ];

    public function entrega()
    {
        return $this->belongsTo(Entrega::class);
    }

    /**
     * Tiempo transcurrido desde que se creó el reporte, en formato legible.
     * Ej: "hace 3 horas", "hace 2 días"
     */
    protected function tiempoTranscurrido(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at?->diffForHumans(),
        );
    }
}