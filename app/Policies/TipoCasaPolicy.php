<?php

namespace App\Policies;

use App\Models\TipoCasa;
use App\Models\User;

class TipoCasaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, TipoCasa $tipoCasa): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }

    public function update(User $user, TipoCasa $tipoCasa): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }

    public function delete(User $user, TipoCasa $tipoCasa): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }
}