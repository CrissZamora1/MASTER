<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $fillable = ['nombre', 'ubicacion', 'descripcion', 'activo'];

    public function tiposCasa()
    {
        return $this->hasMany(TipoCasa::class);
    }

    public function casas()
    {
        return $this->hasMany(Casa::class);
    }
}