<?php

namespace App\Policies;

use App\Models\Proyecto;
use App\Models\User;

class ProyectoPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Proyecto $proyecto): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }

    public function update(User $user, Proyecto $proyecto): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }

    public function delete(User $user, Proyecto $proyecto): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }
}