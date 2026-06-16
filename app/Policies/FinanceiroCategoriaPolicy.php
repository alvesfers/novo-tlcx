<?php

namespace App\Policies;

use App\Models\FinanceiroCategoria;
use App\Models\User;

class FinanceiroCategoriaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isDiocese() || $user->isNucleo() || $user->isSecretaria();
    }

    public function view(User $user, FinanceiroCategoria $categoria): bool
    {
        // Admin vê tudo
        if ($user->isAdmin()) {
            return true;
        }

        // Entidade vê suas próprias categorias
        if ($user->entidade_id === $categoria->entidade_id) {
            return true;
        }

        // Diocese vê categorias de filhos
        if ($user->isDiocese()) {
            return $categoria->entidade->entidade_pai_id === $user->entidade_id;
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

    public function update(User $user, FinanceiroCategoria $categoria): bool
    {
        // Admin edita tudo
        if ($user->isAdmin()) {
            return true;
        }

        // Entidade edita suas próprias categorias
        if ($user->entidade_id === $categoria->entidade_id) {
            return true;
        }

        // Diocese edita categorias de filhos
        if ($user->isDiocese()) {
            return $categoria->entidade->entidade_pai_id === $user->entidade_id;
        }

        return false;
    }

    public function delete(User $user, FinanceiroCategoria $categoria): bool
    {
        // Somente admin pode deletar
        return $user->isAdmin();
    }
}
