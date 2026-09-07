<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReclamoGarantiaFoto extends Model
{
    protected $fillable = ['reclamo_garantia_id', 'ruta'];

    public function reclamoGarantia()
    {
        return $this->belongsTo(ReclamoGarantia::class);
    }
}