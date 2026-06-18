<?php

namespace App\Policies;

use App\Models\Habilidade;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class HabilidadePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Habilidade $habilidade): bool
    {
        return true;
    }

    public function delete(User $user, Habilidade $habilidade): bool
    {
        return true;
    }
}
