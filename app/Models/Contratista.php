<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contratista extends Model
{
    protected $fillable = [
        'nombre', 'especialidad', 'telefono', 'email', 'activo',
    ];
}