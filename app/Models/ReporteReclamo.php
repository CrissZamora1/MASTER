<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporteReclamo extends Model
{
    protected $fillable = [
        'reclamo_id', 'contratista_id', 'descripcion', 'foto', 'estado',
    ];

    public function reclamo()
    {
        return $this->belongsTo(Reclamo::class);
    }

    public function contratista()
    {
        return $this->belongsTo(Contratista::class);
    }
}