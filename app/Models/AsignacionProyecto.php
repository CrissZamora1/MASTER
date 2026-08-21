<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionProyecto extends Model
{
    protected $fillable = ['user_id', 'proyecto_id'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }
}