<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoCasa extends Model
{
    protected $table = 'tipo_casas';

    protected $fillable = [
        'proyecto_id', 'nombre', 'metros', 'observaciones',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function casas()
    {
        return $this->hasMany(Casa::class);
    }
}