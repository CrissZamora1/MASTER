<?php

namespace App\Policies;

use App\Models\Reclamo;
use App\Models\User;

class ReclamoPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Reclamo $reclamo): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN', 'SUP']);
    }

    public function update(User $user, Reclamo $reclamo): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN', 'SUP']);
    }

    public function delete(User $user, Reclamo $reclamo): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }
}