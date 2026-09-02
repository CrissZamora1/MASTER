<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property Carbon|null $fecha_inicio
 * @property Carbon|null $fecha_fin
 * @property Carbon|null $fecha_reporte
 */
class Reclamo extends Model
{
    protected $fillable = [
        'casa_id', 'garantia_id', 'fecha_inicio', 'fecha_fin',
        'estado', 'descripcion', 'ticket', 'fecha_reporte', 'validado_manualmente',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_reporte' => 'date',
        'validado_manualmente' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Reclamo $reclamo) {
            // Si tiene validación manual, respeta eso
            if ($reclamo->validado_manualmente) {
                $reclamo->estado = 'garantia_aceptada';
                return;
            }

            // fecha_inicio = fecha de la última entrega
            $ultimaEntrega = $reclamo->casa?->ultimaEntrega;

            if ($ultimaEntrega && in_array($ultimaEntrega->resultado, ['entregada', 'entregada_con_reclamos'])) {
                /** @var Carbon $fechaEntrega */
                $fechaEntrega = $ultimaEntrega->fecha_hora_entrega;
                $reclamo->fecha_inicio = $fechaEntrega->startOfDay();
            }

            // fecha_fin = fecha_inicio + meses de la garantía
            if ($reclamo->fecha_inicio && $reclamo->garantia) {
                $meses = (int) $reclamo->garantia->meses_duracion;
                $reclamo->fecha_fin = Carbon::parse($reclamo->fecha_inicio)->addMonths($meses)->startOfDay();
            }

            // Estado automático según vencimiento
            if ($reclamo->fecha_fin instanceof Carbon && now()->startOfDay()->gt($reclamo->fecha_fin)) {
                $reclamo->estado = 'fuera_de_garantia';
            } elseif ($reclamo->fecha_fin) {
                $reclamo->estado = 'pendiente';
            }
        });
    }

    public function casa()
    {
        return $this->belongsTo(Casa::class);
    }

    public function garantia()
    {
        return $this->belongsTo(Garantia::class);
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