<?php

namespace App\Policies;

use App\Models\Cita;
use App\Models\User;

class CitaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Cita $cita): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN', 'SUP']);
    }

    public function update(User $user, Cita $cita): bool
    {
        if (! in_array($user->rol?->codigo, ['SUPER', 'ADMIN', 'SUP'])) {
            return false;
        }

        // El bloqueo de 2 horas solo aplica a Supervisores
        if ($user->esSupervisor() && $cita->bloqueada) {
            return false;
        }

        return true;
    }

    public function delete(User $user, Cita $cita): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }
}