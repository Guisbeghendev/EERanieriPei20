<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models (listar usuários).
     * Apenas administradores podem ver a lista completa de usuários.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the model (visualizar um usuário específico).
     * Administradores podem ver qualquer usuário. Usuários podem ver seu próprio perfil.
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasRole('admin') || $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models (criar novos usuários).
     * Apenas administradores podem criar novos usuários.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model (atualizar um usuário).
     * Administradores podem atualizar qualquer usuário. Usuários podem atualizar seu próprio perfil.
     */
    public function update(User $user, User $model): bool
    {
        return $user->hasRole('admin') || $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model (deletar um usuário).
     * Administradores podem deletar qualquer usuário, exceto a si mesmos para evitar autodeleção.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('admin') && $user->id !== $model->id;
    }

    // Não há necessidade de 'restore' ou 'forceDelete' aqui, a menos que o projeto implemente Soft Deletes para User.
}
