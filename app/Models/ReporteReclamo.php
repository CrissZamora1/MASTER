<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporteReclamo extends Model
{
    protected $fillable = [
        'reclamo_id',
        'creado_por_user_id',
        'descripcion',
        'revisado',         // <--- Agrégalo aquí
        'fecha_revision',   // <--- Agrégalo aquí
        // ... los demás campos que ya tengas
    ];
    protected $casts = [
        'revisado' => 'boolean',
        'fecha_revision' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($reporte) {
            if (auth()->check() && ! $reporte->creado_por_user_id) {
                $reporte->creado_por_user_id = auth()->id();
            }
        });
    }

    public function reclamo()
    {
        return $this->belongsTo(Reclamo::class);
    }

    public function contratista()
    {
        return $this->belongsTo(Contratista::class);
    }

    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'creado_por_user_id');
    }

    public function esDeSupervisor(): bool
    {
        return $this->creadoPor?->esSupervisor() ?? false;
    }

    public function esDeContratista(): bool
    {
        return $this->creadoPor?->esContratista() ?? false;
    }
}