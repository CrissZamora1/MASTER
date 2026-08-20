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
        'proyecto_id', 'tipo_casa_id', 'numero_casa',
        'cluster', 'anexo', 'acabados', 'estado',
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

    public function actualizarEstado(): void
    {
        $ultimaEntrega = $this->ultimaEntrega;

        if ($ultimaEntrega && in_array($ultimaEntrega->resultado, ['entregada', 'entregada_con_reclamos'])) {
            $this->estado = 'entregado';
            $this->save();
            return;
        }

        $ultimaCita = $this->ultimaCita;

        if ($ultimaCita) {
            $this->estado = $ultimaCita->estado;
            $this->save();
            return;
        }
    }
}
