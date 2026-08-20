<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Reclamo extends Model
{
    protected $fillable = [
        'casa_id', 'garantia_id', 'fecha_inicio', 'fecha_fin',
        'estado', 'descripcion', 'ticket', 'fecha_reporte',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_reporte' => 'date',
    ];

    public function casa()
    {
        return $this->belongsTo(Casa::class);
    }

    public function garantia()
    {
        return $this->belongsTo(Garantia::class);
    }

    /**
     * Cliente relacionado a este reclamo, a través de la casa
     * (jalado de la última entrega registrada de esa casa).
     */
    protected function cliente(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->casa?->ultimaEntrega?->cliente,
        );
    }
}