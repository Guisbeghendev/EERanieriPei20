<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Group;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any groups (listar grupos).
     * Qualquer usuário autenticado pode ver a lista de grupos.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view a specific group.
     * Qualquer usuário autenticado pode ver um grupo específico.
     */
    public function view(User $user, Group $group): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create groups.
     * Apenas administradores podem criar grupos.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the group.
     * Apenas administradores podem atualizar grupos.
     */
    public function update(User $user, Group $group): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the group.
     * Apenas administradores podem deletar grupos.
     */
    public function delete(User $user, Group $group): bool
    {
        return $user->hasRole('admin');
    }
}
