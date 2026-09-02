<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Reclamo extends Model
{
    protected $fillable = [
        'casa_id', 'descripcion', 'ticket', 'fecha_reporte',
    ];

    protected $casts = [
        'fecha_reporte' => 'date',
    ];

    public function casa()
    {
        return $this->belongsTo(Casa::class);
    }

    public function garantias()
    {
        return $this->hasMany(ReclamoGarantia::class);
    }

    public function reportes()
    {
        return $this->hasMany(ReporteReclamo::class);
    }

    protected function cliente(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->casa?->ultimaEntrega?->cliente,
        );
    }
}