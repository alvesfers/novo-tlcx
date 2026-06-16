<?php

namespace App\Policies;

use App\Models\Dirigente;
use App\Models\User;

class DirigentPolicy
{
    /**
     * Admin vê tudo, Diocese vê dirigentes de sua estrutura
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Quem pode ver um dirigente específico
     */
    public function view(User $user, Dirigente $dirigente): bool
    {
        // Admin vê tudo
        if ($user->isAdmin()) {
            return true;
        }

        // Qualquer usuário pode visualizar dirigentes (leitura)
        // Restrição mais forte está no update/delete
        return true;
    }

    /**
     * Apenas admin e Diocese podem criar dirigentes
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isDiocese();
    }

    /**
     * Admin e Diocese podem editar dirigentes de sua estrutura
     */
    public function update(User $user, Dirigente $dirigente): bool
    {
        // Admin edita tudo
        if ($user->isAdmin()) {
            return true;
        }

        // Diocese edita dirigentes de sua estrutura
        if ($user->isDiocese()) {
            // Verificar se dirigente tem vínculo em alguma entidade da diocese
            return $dirigente->vinculos()
                ->whereHas('entidade', function ($query) use ($user) {
                    // Entidades da diocese (filha direta ou a própria)
                    $query->where('id', $user->entidade_id)
                        ->orWhere('entidade_pai_id', $user->entidade_id);
                })
                ->exists();
        }

        // Núcleo/Secretaria edita dirigentes que têm vínculo com sua entidade
        if ($user->isNucleo() || $user->isSecretaria()) {
            return $dirigente->vinculos()
                ->where('entidade_id', $user->entidade_id)
                ->exists();
        }

        return false;
    }

    /**
     * Apenas admin pode deletar dirigentes
     */
    public function delete(User $user, Dirigente $dirigente): bool
    {
        return $user->isAdmin();
    }

    /**
     * Apenas admin pode restaurar
     */
    public function restore(User $user, Dirigente $dirigente): bool
    {
        return $user->isAdmin();
    }

    /**
     * Apenas admin pode forçar deleção
     */
    public function forceDelete(User $user, Dirigente $dirigente): bool
    {
        return $user->isAdmin();
    }

    /**
     * Quem pode adicionar/editar vínculos
     */
    public function manageVinculos(User $user, Dirigente $dirigente): bool
    {
        // Admin pode tudo
        if ($user->isAdmin()) {
            return true;
        }

        // Diocese gerencia vínculos de sua estrutura
        if ($user->isDiocese()) {
            return $dirigente->vinculos()
                ->whereHas('entidade', function ($query) use ($user) {
                    $query->where('id', $user->entidade_id)
                        ->orWhere('entidade_pai_id', $user->entidade_id);
                })
                ->exists();
        }

        // Núcleo/Secretaria gerencia vínculos de sua entidade
        if ($user->isNucleo() || $user->isSecretaria()) {
            return $dirigente->vinculos()
                ->where('entidade_id', $user->entidade_id)
                ->exists();
        }

        return false;
    }
}
