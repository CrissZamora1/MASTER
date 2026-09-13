<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ReporteEntrega extends Model
{
    protected $fillable = [
        'entrega_id',
        'descripcion',
        'estado',
        'encargado',
        'reclamo_id',
    ];

    public function entrega()
    {
        return $this->belongsTo(Entrega::class);
    }

    public function fotos()
    {
        return $this->hasMany(ReporteEntregaFoto::class);
    }

    public function reclamo()
    {
        return $this->belongsTo(Reclamo::class);
    }

    protected function tiempoTranscurrido(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_at?->diffForHumans(),
        );
    }
}
