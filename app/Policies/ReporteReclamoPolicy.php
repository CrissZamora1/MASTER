<?php

namespace App\Policies;

use App\Models\ReporteReclamo;
use App\Models\User;

class ReporteReclamoPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ReporteReclamo $reporteReclamo): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN', 'SUP', 'CONT']);
    }

    public function update(User $user, ReporteReclamo $reporteReclamo): bool
    {
        return in_array($user->rol?->codigo, ['SUP', 'CONT']);
    }

    public function delete(User $user, ReporteReclamo $reporteReclamo): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }
}