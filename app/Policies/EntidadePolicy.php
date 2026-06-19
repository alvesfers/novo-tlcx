<?php

namespace App\Policies;

use App\Enums\TipoUsuario;
use App\Enums\TipoEntidade;
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
     * Quem pode ver uma entidade específica (show pages são públicas para usuários logados)
     */
    public function view(User $user, Entidade $entidade): bool
    {
        // Admin vê tudo
        if ($user->isAdmin()) {
            return true;
        }

        // Qualquer usuário logado pode ver dioceses, núcleos ou secretarias (show pages públicas)
        return true;
    }

    /**
     * Criar entidades - regras por tipo
     */
    public function create(User $user): bool
    {
        // Admin pode criar tudo
        if ($user->isAdmin()) {
            return true;
        }

        // Diocese pode criar núcleos/secretarias (entidades filhas)
        if ($user->isDiocese()) {
            return true;
        }

        return false;
    }

    /**
     * Editar entidades - regras por tipo e escopo
     */
    public function update(User $user, Entidade $entidade): bool
    {
        // Admin edita tudo
        if ($user->isAdmin()) {
            return true;
        }

        // **DIOCESES:** Diocese edita sua própria diocese
        if ($entidade->tipo_entidade?->isDiocese()) {
            return $user->isDiocese() && $user->entidade_id === $entidade->id;
        }

        // **NÚCLEOS:** Diocese edita tudo; Núcleo edita só sua própria
        if ($entidade->tipo_entidade?->isNucleo()) {
            if ($user->isDiocese()) {
                return true; // Diocese edita todos os núcleos
            }
            if ($user->isNucleo() && $user->entidade_id === $entidade->id) {
                return true; // Núcleo edita a si mesmo
            }
            return false;
        }

        // **SECRETARIAS:** Diocese edita tudo; Secretaria edita só sua própria
        if ($entidade->tipo_entidade?->isSecretaria()) {
            if ($user->isDiocese()) {
                return true; // Diocese edita todas as secretarias
            }
            if ($user->isSecretaria() && $user->entidade_id === $entidade->id) {
                return true; // Secretaria edita a si mesma
            }
            return false;
        }

        return false;
    }

    /**
     * Deletar entidades - regras por tipo e escopo
     */
    public function delete(User $user, Entidade $entidade): bool
    {
        // Admin pode deletar tudo
        if ($user->isAdmin()) {
            return true;
        }

        // **DIOCESES:** Diocese pode deletar sua própria diocese
        if ($entidade->tipo_entidade?->isDiocese()) {
            return $user->isDiocese() && $user->entidade_id === $entidade->id;
        }

        // **NÚCLEOS:** Diocese pode deletar tudo; Núcleo pode deletar a si mesmo
        if ($entidade->tipo_entidade?->isNucleo()) {
            if ($user->isDiocese()) {
                return true; // Diocese deleta todos os núcleos
            }
            if ($user->isNucleo() && $user->entidade_id === $entidade->id) {
                return true; // Núcleo deleta a si mesmo
            }
            return false;
        }

        // **SECRETARIAS:** Diocese pode deletar tudo; Secretaria pode deletar a si mesma
        if ($entidade->tipo_entidade?->isSecretaria()) {
            if ($user->isDiocese()) {
                return true; // Diocese deleta todas as secretarias
            }
            if ($user->isSecretaria() && $user->entidade_id === $entidade->id) {
                return true; // Secretaria deleta a si mesma
            }
            return false;
        }

        return false;
    }

    /**
     * Deletar múltiplas entidades - regras por tipo
     */
    public function deleteMultiple(User $user): bool
    {
        // Admin pode deletar tudo
        if ($user->isAdmin()) {
            return true;
        }

        // Diocese pode deletar múltiplos
        if ($user->isDiocese()) {
            return true;
        }

        // Núcleo pode deletar múltiplos (entidades filhas)
        if ($user->isNucleo()) {
            return true;
        }

        return false;
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
