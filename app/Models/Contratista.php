<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contratista extends Model
{
    protected $fillable = [
        'nombre', 'especialidad', 'telefono', 'email', 'activo', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}