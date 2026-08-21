<?php

namespace App\Policies;

use App\Models\Garantia;
use App\Models\User;

class GarantiaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Garantia $garantia): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }

    public function update(User $user, Garantia $garantia): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }

    public function delete(User $user, Garantia $garantia): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }
}