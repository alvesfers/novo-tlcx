<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, User $model): bool
    {
        // Admin vê todos os usuários
        if ($user->isAdmin()) {
            return true;
        }

        // Diocese vê usuários de sua diocese
        if ($user->isDiocese() && $model->entidade_id === $user->entidade_id) {
            return true;
        }

        // Usuário vê a si mesmo
        if ($user->id === $model->id) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isSecretaria();
    }

    public function update(User $user, User $model): bool
    {
        // Admin edita qualquer usuário
        if ($user->isAdmin()) {
            return true;
        }

        // Secretaria edita usuários de sua secretaria/entidade
        if ($user->isSecretaria() && $model->entidade_id === $user->entidade_id) {
            return true;
        }

        // Diocese edita usuários de sua diocese
        if ($user->isDiocese() && $model->entidade_id === $user->entidade_id) {
            return true;
        }

        // Usuário edita sua própria conta
        if ($user->id === $model->id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    public function linkUser(User $user, User $model): bool
    {
        // Admin pode vincular qualquer usuário
        if ($user->isAdmin()) {
            return true;
        }

        // Diocese pode vincular usuários de sua diocese
        if ($user->isDiocese() && $model->entidade_id === $user->entidade_id) {
            return true;
        }

        // Secretaria pode vincular usuários de sua secretaria/entidade
        if ($user->isSecretaria() && $model->entidade_id === $user->entidade_id) {
            return true;
        }

        return false;
    }
}
