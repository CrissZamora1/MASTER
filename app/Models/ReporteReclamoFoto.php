<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporteReclamoFoto extends Model
{
    protected $fillable = ['reporte_reclamo_id', 'ruta'];

    public function reporte()
    {
        return $this->belongsTo(ReporteReclamo::class, 'reporte_reclamo_id');
    }
}