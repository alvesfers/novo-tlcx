<?php

namespace App\Policies;

use App\Models\AlmoxarifadoItem;
use App\Models\User;

class AlmoxarifadoItemPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->tipo_usuario === 'admin') {
            return true;
        }

        return $user->entidade !== null;
    }

    public function view(User $user, AlmoxarifadoItem $item): bool
    {
        if ($user->tipo_usuario === 'admin') {
            return true;
        }

        if (!$user->entidade) {
            return false;
        }

        return $user->entidade->id === $item->entidade_id;
    }

    public function create(User $user): bool
    {
        if ($user->tipo_usuario === 'admin') {
            return true;
        }

        return $user->entidade !== null;
    }

    public function update(User $user, AlmoxarifadoItem $item): bool
    {
        if ($user->tipo_usuario === 'admin') {
            return true;
        }

        if (!$user->entidade) {
            return false;
        }

        return $user->entidade->id === $item->entidade_id;
    }

    public function delete(User $user, AlmoxarifadoItem $item): bool
    {
        return $this->update($user, $item);
    }
}
