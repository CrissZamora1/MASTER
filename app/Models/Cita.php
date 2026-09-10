<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Cita extends Model
{
    protected $fillable = [
        'casa_id',
        'cliente_id',
        'fecha_hora',
        'estado',
        'cita_previa_id',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Cita $cita) {
            $cita->estado = $cita->cita_previa_id ? 'reprogramada' : 'programada';
        });
    }

    public function casa()
    {
        return $this->belongsTo(Casa::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function entrega()
    {
        return $this->hasOne(Entrega::class);
    }

    public function citaPrevia()
    {
        return $this->belongsTo(Cita::class, 'cita_previa_id');
    }

    protected function bloqueada(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::now()->greaterThan(
                $this->fecha_hora->copy()->addHours(2)
            ),
        );
    }
}
