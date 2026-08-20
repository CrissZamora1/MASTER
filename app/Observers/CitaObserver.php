<?php

namespace App\Observers;

use App\Models\Cita;

class CitaObserver
{
    public function saved(Cita $cita): void
    {
        $cita->casa?->actualizarEstado();
    }
}