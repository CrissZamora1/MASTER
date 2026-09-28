<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporteEntregaFoto extends Model
{
    protected $fillable = [
        'entrega_id',
        'ruta',
        'ruta_final',
        'descripcion',
        'estado',
        'aprobado_por',
        'aprobado_at',
        'reclamo_id',
        'contratista_id',
    ];

    protected $casts = [
        'aprobado_at' => 'datetime',
    ];

    public function entrega()
    {
        return $this->belongsTo(Entrega::class);
    }

    public function aprobadoPor()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    public function reclamo()
    {
        return $this->belongsTo(Reclamo::class);
    }

    public function marcarVistoBueno(): void
    {
        $this->update([
            'estado' => 'finalizado',
            'aprobado_por' => auth()->id(),
            'aprobado_at' => now(),
        ]);
    }

    public function contratista()
    {
        return $this->belongsTo(Contratista::class);
    }
}
