<?php

namespace App\Policies;

use App\Models\Entrega;
use App\Models\User;

class EntregaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Entrega $entrega): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN', 'SUP']);
    }

    public function update(User $user, Entrega $entrega): bool
    {
        if (! in_array($user->rol?->codigo, ['SUPER', 'ADMIN', 'SUP'])) {
            return false;
        }

        if ($user->esSupervisor() && $entrega->created_at->lt(now()->subHours(2))) {
            return false;
        }

        return true;
    }

    public function delete(User $user, Entrega $entrega): bool
    {
        if (in_array($user->rol?->codigo, ['SUPER', 'ADMIN'])) {
            return true;
        }

        if ($user->esSupervisor() && $entrega->created_at->gt(now()->subHours(2))) {
            return true;
        }

        return false;
    }
}