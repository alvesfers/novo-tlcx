<?php

namespace App\Policies;

use App\Models\Evento;
use App\Models\User;

class EventoPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Evento $evento): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDiocese()) {
            $entidadeUsuario = $user->entidade;
            if (!$entidadeUsuario) {
                return false;
            }

            // Pode visualizar se criado pela sua diocese ou por filhas
            if ($evento->entidade_criadora_id === $entidadeUsuario->id) {
                return true;
            }

            // Ou se sua entidade filha criou
            return $evento->entidadeCriadora->isFilhaDe($entidadeUsuario->id);
        }

        if ($user->isNucleo() || $user->isSecretaria()) {
            $entidadeUsuario = $user->entidade;
            if (!$entidadeUsuario) {
                return false;
            }

            // Pode visualizar se criou o evento
            if ($evento->entidade_criadora_id === $entidadeUsuario->id) {
                return true;
            }

            // Ou se a entidade do usuário participa do evento
            return $evento->entidades()->where('entidade_id', $entidadeUsuario->id)->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isDiocese() || $user->isNucleo() || $user->isSecretaria();
    }

    public function update(User $user, Evento $evento): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDiocese()) {
            $entidadeUsuario = $user->entidade;
            if (!$entidadeUsuario) {
                return false;
            }

            // Pode editar se criado pela sua diocese ou por filhas
            if ($evento->entidade_criadora_id === $entidadeUsuario->id) {
                return true;
            }

            return $evento->entidadeCriadora->isFilhaDe($entidadeUsuario->id);
        }

        if ($user->isNucleo() || $user->isSecretaria()) {
            $entidadeUsuario = $user->entidade;
            if (!$entidadeUsuario) {
                return false;
            }

            // Só edita eventos criados pela própria entidade
            return $evento->entidade_criadora_id === $entidadeUsuario->id;
        }

        return false;
    }

    public function delete(User $user, Evento $evento): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDiocese()) {
            $entidadeUsuario = $user->entidade;
            if (!$entidadeUsuario) {
                return false;
            }

            if ($evento->entidade_criadora_id === $entidadeUsuario->id) {
                return true;
            }

            return $evento->entidadeCriadora->isFilhaDe($entidadeUsuario->id);
        }

        if ($user->isNucleo() || $user->isSecretaria()) {
            $entidadeUsuario = $user->entidade;
            if (!$entidadeUsuario) {
                return false;
            }

            return $evento->entidade_criadora_id === $entidadeUsuario->id;
        }

        return false;
    }

    public function manageParticipantes(User $user, Evento $evento): bool
    {
        return $this->update($user, $evento);
    }
}
