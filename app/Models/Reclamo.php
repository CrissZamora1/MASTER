<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Reclamo extends Model
{
    protected $fillable = [
        'casa_id', 'descripcion', 'ticket', 'fecha_reporte',
    ];

    protected $casts = [
        'fecha_reporte' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Reclamo $reclamo) {
            $reclamo->ticket ??= 'TK-' . strtoupper(Str::random(8));
        });
    }

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