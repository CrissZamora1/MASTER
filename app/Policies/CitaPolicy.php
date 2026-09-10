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
        if ($user->esMaster() || $user->esSuper()) {
            return true;
        }

        if ($user->esAdmin() || $user->esSupervisor()) {
            return ! $cita->bloqueada;
        }

        return false;
    }

    public function delete(User $user, Cita $cita): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }
}
