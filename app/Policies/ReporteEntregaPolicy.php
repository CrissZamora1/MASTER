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
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN', 'SUP']);
    }

    public function update(User $user, ReporteEntrega $reporteEntrega): bool
    {
        if (in_array($user->rol?->codigo, ['SUPER', 'ADMIN'])) {
            return true;
        }

        if ($user->esSupervisor() && $reporteEntrega->created_at->gt(now()->subHours(2))) {
            return true;
        }

        return false;
    }

    public function delete(User $user, ReporteEntrega $reporteEntrega): bool
    {
        if (in_array($user->rol?->codigo, ['SUPER', 'ADMIN'])) {
            return true;
        }

        if ($user->esSupervisor() && $reporteEntrega->created_at->gt(now()->subHours(2))) {
            return true;
        }

        return false;
    }
}