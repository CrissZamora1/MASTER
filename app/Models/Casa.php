<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Casa extends Model
{
    protected static function booted(): void
    {
        static::saving(function (Casa $casa) {
            if ($casa->isDirty('acabados') && $casa->acabados && $casa->estado === 'no_disponible') {
                $casa->estado = 'disponible';
            }
        });
    }

    protected $fillable = [
        'proyecto_id',
        'tipo_casa_id',
        'numero_casa',
        'cluster',
        'anexo',
        'acabados',
        'estado',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function tipoCasa()
    {
        return $this->belongsTo(TipoCasa::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function ultimaCita()
    {
        return $this->hasOne(Cita::class)->latestOfMany('fecha_hora');
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class);
    }

    public function ultimaEntrega()
    {
        return $this->hasOne(Entrega::class)->latestOfMany('fecha_hora_entrega');
    }

    public function reclamos()
    {
        return $this->hasMany(Reclamo::class);
    }

    public function actualizarEstado(): void
    {
        $ultimaEntrega = $this->ultimaEntrega;
        $ultimaCita = $this->ultimaCita;

        $entregaEsMasReciente = $ultimaEntrega
            && (! $ultimaCita || $ultimaEntrega->fecha_hora_entrega->gte($ultimaCita->fecha_hora));

        if ($entregaEsMasReciente) {
            $this->estado = match ($ultimaEntrega->resultado) {
                'entregada' => 'entregado',
                'entregada_con_reclamos' => 'entregado_con_reclamos',
                'no_entregada' => 'no_asistio',
                default => $this->estado,
            };
            $this->save();
            return;
        }

        if ($ultimaCita) {
            $this->estado = $ultimaCita->estado;
            $this->save();
        }
    }
}
