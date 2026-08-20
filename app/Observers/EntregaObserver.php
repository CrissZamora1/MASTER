<?php

namespace App\Observers;

use App\Models\Entrega;

class EntregaObserver
{
    public function saved(Entrega $entrega): void
    {
        $entrega->casa?->actualizarEstado();
    }
}