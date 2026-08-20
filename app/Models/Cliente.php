<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nombre', 'apellido', 'dpi', 'telefono', 'email',
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class);
    }
}