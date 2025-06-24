<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the profile.
     * Perfis são públicos para visualização por qualquer usuário autenticado.
     * Para visitantes (guests), a lógica de visualização deve ser tratada em rotas ou Gates específicas,
     * se houver um requisito para perfis públicos globais.
     */
    public function view(User $user, Profile $profile): bool
    {
        return true; // Qualquer usuário autenticado pode ver qualquer perfil
    }

    /**
     * Determine whether the user can update the profile.
     * Administradores podem atualizar qualquer perfil. Usuários podem atualizar seu próprio perfil.
     */
    public function update(User $user, Profile $profile): bool
    {
        return $user->hasRole('admin') || $user->id === $profile->user_id;
    }

    // Não há 'create' porque o perfil é criado automaticamente com o usuário (UserObserver).
    // Não há 'delete' porque o perfil é deletado em cascata com o usuário.
}
