<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Cita extends Model
{
    protected $fillable = [
        'casa_id', 'cliente_id', 'tipo_cita', 'fecha_hora', 'estado',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    public function casa()
    {
        return $this->belongsTo(Casa::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * true si ya pasaron más de 2 horas desde la hora asignada de la cita.
     * Se usa para bloquear la edición.
     */
    protected function bloqueada(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::now()->greaterThan(
                $this->fecha_hora->copy()->addHours(2)
            ),
        );
    }
}