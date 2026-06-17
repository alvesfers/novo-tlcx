<?php

namespace App\Policies;

use App\Models\DocumentoCategoria;
use App\Models\User;

class DocumentoCategoriaPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->tipo_usuario === 'admin') {
            return true;
        }

        return $user->entidade !== null;
    }

    public function view(User $user, DocumentoCategoria $categoria): bool
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

    public function update(User $user, DocumentoCategoria $categoria): bool
    {
        if ($user->tipo_usuario === 'admin') {
            return true;
        }

        if (!$user->entidade) {
            return false;
        }

        return $user->entidade->id === $categoria->entidade_id;
    }

    public function delete(User $user, DocumentoCategoria $categoria): bool
    {
        return $this->update($user, $categoria);
    }
}
