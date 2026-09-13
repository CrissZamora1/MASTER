<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporteEntregaFoto extends Model
{
    protected $fillable = ['reporte_entrega_id', 'ruta'];

    public function reporte()
    {
        return $this->belongsTo(ReporteEntrega::class, 'reporte_entrega_id');
    }
}
