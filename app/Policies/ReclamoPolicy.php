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
        if (! in_array($user->rol?->codigo, ['SUPER', 'ADMIN', 'SUP'])) {
            return false;
        }

        if ($user->esSupervisor() && $reclamo->created_at->lt(now()->subHours(2))) {
            return false;
        }

        return true;
    }

    public function delete(User $user, Reclamo $reclamo): bool
    {
        if (in_array($user->rol?->codigo, ['SUPER', 'ADMIN'])) {
            return true;
        }

        if ($user->esSupervisor() && $reclamo->created_at->gt(now()->subHours(2))) {
            return true;
        }

        return false;
    }
}