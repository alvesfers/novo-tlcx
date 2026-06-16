<?php

namespace App\Policies;

use App\Enums\TipoUsuario;
use App\Models\Entidade;
use App\Models\User;

class EntidadePolicy
{
    /**
     * Admin vê tudo, Diocese vê estrutura dela
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Quem pode ver uma entidade específica
     */
    public function view(User $user, Entidade $entidade): bool
    {
        // Admin vê tudo
        if ($user->isAdmin()) {
            return true;
        }

        // Usuário diocesano vê sua diocese e estrutura
        if ($user->isdiocese() && $user->entidade_id === $entidade->id) {
            return true;
        }

        // Usuário diocesano vê núcleos e secretarias filhas
        if ($user->isDiocese() && $entidade->entidade_pai_id === $user->entidade_id) {
            return true;
        }

        // Usuário de núcleo/secretaria vê sua entidade
        if ($user->entidade_id === $entidade->id) {
            return true;
        }

        // Usuário de núcleo/secretaria vê sua diocese
        if ($entidade->isdiocese() && $entidade->id === $user->entidade->entidade_pai_id) {
            return true;
        }

        return false;
    }

    /**
     * Apenas admin e Diocese podem criar entidades
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isDiocese();
    }

    /**
     * Admin e Diocese podem editar entidades filhas
     */
    public function update(User $user, Entidade $entidade): bool
    {
        // Admin edita tudo
        if ($user->isAdmin()) {
            return true;
        }

        // Diocese edita sua própria entidade
        if ($user->isDiocese() && $user->entidade_id === $entidade->id) {
            return true;
        }

        // Diocese edita núcleos/secretarias filhas
        if ($user->isDiocese() && $entidade->entidade_pai_id === $user->entidade_id) {
            return true;
        }

        // Usuário de núcleo/secretaria edita sua própria entidade (dados básicos)
        if ($user->entidade_id === $entidade->id) {
            return true;
        }

        return false;
    }

    /**
     * Apenas admin pode deletar entidades
     */
    public function delete(User $user, Entidade $entidade): bool
    {
        return $user->isAdmin();
    }

    /**
     * Apenas admin pode restaurar
     */
    public function restore(User $user, Entidade $entidade): bool
    {
        return $user->isAdmin();
    }

    /**
     * Apenas admin pode forçar deleção
     */
    public function forceDelete(User $user, Entidade $entidade): bool
    {
        return $user->isAdmin();
    }

    /**
     * Apenas Diocese pode gerenciar entidades filhas
     */
    public function manageChildren(User $user, Entidade $entidade): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Apenas Diocese pode gerenciar filhas (não Núcleo/Secretaria)
        if ($user->isDiocese() && $user->entidade_id === $entidade->id) {
            return true;
        }

        return false;
    }
}
