<?php

namespace App\Policies;

use App\Models\ReporteEntrega;
use App\Models\User;

class ReporteEntregaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ReporteEntrega $reporteEntrega): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        // Recuerda: confirmaste que el reporte de entrega SÍ lo puede crear el SUPER/ADMIN
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }

    public function update(User $user, ReporteEntrega $reporteEntrega): bool
    {
        return false;
    }

    public function delete(User $user, ReporteEntrega $reporteEntrega): bool
    {
        return false;
    }
}