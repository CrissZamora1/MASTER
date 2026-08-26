<?php

namespace App\Policies;

use App\Models\Cliente;
use App\Models\User;

class ClientePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Cliente $cliente): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN', 'SUP']);
    }

    public function update(User $user, Cliente $cliente): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN', 'SUP']);
    }

    public function delete(User $user, Cliente $cliente): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }
}