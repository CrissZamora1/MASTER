<?php

namespace App\Policies;

use App\Models\Cita;
use App\Models\User;

class ClientePolicy
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
        return $user->esMaster() || $user->esSuper() || $user->esAdmin() || $user->esSupervisor();
    }

    public function update(User $user, Cita $cita): bool
    {
        return false;
    }

    public function delete(User $user, Cita $cita): bool
    {
        return $user->esMaster() || $user->esSuper();
    }
}
