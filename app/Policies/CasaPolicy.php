<?php

namespace App\Policies;

use App\Models\Casa;
use App\Models\User;

class CasaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Casa $casa): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }

    public function update(User $user, Casa $casa): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }

    public function delete(User $user, Casa $casa): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }
}