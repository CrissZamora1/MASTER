<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReclamoGarantia extends Model
{
    protected $table = 'reclamo_garantia';

    protected $fillable = [
        'reclamo_id', 'garantia_id', 'fecha_inicio', 'fecha_fin', 'estado', 'validado_manualmente',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'validado_manualmente' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (ReclamoGarantia $item) {
            if ($item->validado_manualmente) {
                $item->estado = 'garantia_aceptada';
                return;
            }

            $ultimaEntrega = $item->reclamo?->casa?->ultimaEntrega;

            if ($ultimaEntrega && in_array($ultimaEntrega->resultado, ['entregada', 'entregada_con_reclamos'])) {
                $item->fecha_inicio = $ultimaEntrega->fecha_hora_entrega->startOfDay()->toDateString();
            }

            if ($item->fecha_inicio && $item->garantia) {
                $item->fecha_fin = \Carbon\Carbon::parse($item->fecha_inicio)
                    ->addMonths($item->garantia->meses_duracion)
                    ->toDateString();
            }

            if ($item->fecha_fin && now()->toDateString() > $item->fecha_fin) {
                $item->estado = 'fuera_de_garantia';
            } elseif ($item->fecha_fin) {
                $item->estado = 'pendiente';
            }
        });
    }

    public function reclamo()
    {
        return $this->belongsTo(Reclamo::class);
    }

    public function garantia()
    {
        return $this->belongsTo(Garantia::class);
    }
}