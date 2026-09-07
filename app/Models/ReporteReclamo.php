<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporteReclamo extends Model
{
    protected $fillable = [
        'reclamo_garantia_id',
        'descripcion',
        'estado',
        'creado_por_user_id',
        'revisado',
        'revisado_por',
        'revisado_at',
    ];

    protected $casts = [
        'revisado' => 'boolean',
        'revisado_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($reporte) {
            if (auth()->check() && ! $reporte->creado_por_user_id) {
                $reporte->creado_por_user_id = auth()->id();
            }
        });
    }

    public function reclamoGarantia()
    {
        return $this->belongsTo(ReclamoGarantia::class);
    }

    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'creado_por_user_id');
    }

    public function revisadoPor()
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    public function fotos()
    {
        return $this->hasMany(ReporteReclamoFoto::class);
    }

    public function esDeSupervisor(): bool
    {
        return $this->creadoPor?->esSupervisor() ?? false;
    }

    public function esDeContratista(): bool
    {
        return $this->creadoPor?->esContratista() ?? false;
    }

    public function marcarRevisado(): void
    {
        $this->update([
            'revisado' => true,
            'revisado_por' => auth()->id(),
            'revisado_at' => now(),
        ]);
    }
}
