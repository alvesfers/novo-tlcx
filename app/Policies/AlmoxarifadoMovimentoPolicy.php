<?php

namespace App\Policies;

use App\Models\AlmoxarifadoMovimento;
use App\Models\User;

class AlmoxarifadoMovimentoPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->tipo_usuario === 'admin') {
            return true;
        }

        return $user->entidade !== null;
    }

    public function view(User $user, AlmoxarifadoMovimento $movimento): bool
    {
        if ($user->tipo_usuario === 'admin') {
            return true;
        }

        if (!$user->entidade) {
            return false;
        }

        return $user->entidade->id === $movimento->entidade_id;
    }

    public function create(User $user): bool
    {
        if ($user->tipo_usuario === 'admin') {
            return true;
        }

        return $user->entidade !== null;
    }

    public function update(User $user, AlmoxarifadoMovimento $movimento): bool
    {
        if ($user->tipo_usuario === 'admin') {
            return true;
        }

        if (!$user->entidade) {
            return false;
        }

        return $user->entidade->id === $movimento->entidade_id;
    }

    public function delete(User $user, AlmoxarifadoMovimento $movimento): bool
    {
        return $this->update($user, $movimento);
    }
}
