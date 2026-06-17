<?php

namespace App\Policies;

use App\Models\Documento;
use App\Models\User;

class DocumentoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user !== null;
    }

    public function view(User $user, Documento $documento): bool
    {
        // Público pode ser visto por qualquer usuário autenticado
        if ($documento->visibilidade->value === 'publico' && $documento->ativo) {
            return true;
        }

        // Privado só pode ser visto pela entidade dona ou admin
        if ($documento->visibilidade->value === 'privado') {
            if ($user->tipo_usuario === 'admin') {
                return true;
            }

            if ($user->entidade && $user->entidade->id === $documento->entidade_id) {
                return true;
            }
        }

        return false;
    }

    public function create(User $user): bool
    {
        if ($user->tipo_usuario === 'admin') {
            return true;
        }

        return $user->entidade !== null;
    }

    public function update(User $user, Documento $documento): bool
    {
        if ($user->tipo_usuario === 'admin') {
            return true;
        }

        if (!$user->entidade) {
            return false;
        }

        return $user->entidade->id === $documento->entidade_id;
    }

    public function delete(User $user, Documento $documento): bool
    {
        return $this->update($user, $documento);
    }

    public function download(User $user, Documento $documento): bool
    {
        return $this->view($user, $documento);
    }
}
