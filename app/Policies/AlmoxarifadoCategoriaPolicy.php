<?php

namespace App\Policies;

use App\Models\AlmoxarifadoCategoria;
use App\Models\User;

class AlmoxarifadoCategoriaPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->tipo_usuario === 'admin') {
            return true;
        }

        return $user->entidade !== null;
    }

    public function view(User $user, AlmoxarifadoCategoria $categoria): bool
    {
        if ($user->tipo_usuario === 'admin') {
            return true;
        }

        if (!$user->entidade) {
            return false;
        }

        return $user->entidade->id === $categoria->entidade_id;
    }

    public function create(User $user): bool
    {
        if ($user->tipo_usuario === 'admin') {
            return true;
        }

        return $user->entidade !== null;
    }

    public function update(User $user, AlmoxarifadoCategoria $categoria): bool
    {
        if ($user->tipo_usuario === 'admin') {
            return true;
        }

        if (!$user->entidade) {
            return false;
        }

        return $user->entidade->id === $categoria->entidade_id;
    }

    public function delete(User $user, AlmoxarifadoCategoria $categoria): bool
    {
        return $this->update($user, $categoria);
    }
}
