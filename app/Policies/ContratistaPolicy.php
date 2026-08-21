<?php

namespace App\Policies;

use App\Models\Contratista;
use App\Models\User;

class ContratistaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Contratista $contratista): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }

    public function update(User $user, Contratista $contratista): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }

    public function delete(User $user, Contratista $contratista): bool
    {
        return in_array($user->rol?->codigo, ['SUPER', 'ADMIN']);
    }
}