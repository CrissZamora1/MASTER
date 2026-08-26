<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->esMaster() || $user->esSuper();
    }

    public function view(User $user, User $model): bool
    {
        return $user->esMaster() || $user->esSuper();
    }

    public function create(User $user): bool
    {
        return $user->esMaster();
    }

    public function update(User $user, User $model): bool
    {
        return $user->esMaster();
    }

    public function delete(User $user, User $model): bool
    {
        return $user->esMaster();
    }
}