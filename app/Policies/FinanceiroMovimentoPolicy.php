<?php

namespace App\Policies;

use App\Models\FinanceiroMovimento;
use App\Models\User;

class FinanceiroMovimentoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isDiocese() || $user->isNucleo() || $user->isSecretaria();
    }

    public function view(User $user, FinanceiroMovimento $movimento): bool
    {
        // Admin vê tudo
        if ($user->isAdmin()) {
            return true;
        }

        // Entidade vê seus próprios movimentos
        if ($user->entidade_id === $movimento->entidade_id) {
            return true;
        }

        // Diocese vê movimentos de filhos
        if ($user->isDiocese()) {
            return $movimento->entidade->entidade_pai_id === $user->entidade_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        // Admin pode criar para qualquer entidade
        if ($user->isAdmin()) {
            return true;
        }

        // Diocese/Núcleo/Secretaria criam para si mesmos
        return $user->isDiocese() || $user->isNucleo() || $user->isSecretaria();
    }

    public function update(User $user, FinanceiroMovimento $movimento): bool
    {
        // Admin edita tudo
        if ($user->isAdmin()) {
            return true;
        }

        // Entidade edita seus próprios movimentos
        if ($user->entidade_id === $movimento->entidade_id) {
            return true;
        }

        // Diocese edita movimentos de filhos (auditoria)
        if ($user->isDiocese()) {
            return $movimento->entidade->entidade_pai_id === $user->entidade_id;
        }

        return false;
    }

    public function delete(User $user, FinanceiroMovimento $movimento): bool
    {
        // Somente admin pode deletar permanentemente
        return $user->isAdmin();
    }
}
