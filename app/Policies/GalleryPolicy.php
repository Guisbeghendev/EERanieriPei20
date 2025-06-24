<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Gallery;
use App\Models\Group; // Importar Group (já existe, mas garantindo)
use Illuminate\Auth\Access\HandlesAuthorization;

class GalleryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models (para listagem de gerenciamento de galerias).
     * Apenas fotógrafos podem ver a lista de galerias para gerenciar (painel do fotógrafo).
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('fotografo');
    }

    /**
     * Determine whether the user can view a specific Gallery (para acesso de usuários autenticados).
     * Esta política lida com a visualização de galerias por usuários autenticados (fotógrafos, donos, membros de grupo).
     * A visualização de galerias **públicas por guests** é tratada na Gate `view-gallery`.
     */
    public function view(User $user, Gallery $gallery): bool
    {
        // Fotógrafos (administradores do conteúdo) sempre podem ver qualquer galeria.
        if ($user->hasRole('fotografo')) {
            return true;
        }

        // O dono da galeria sempre pode ver sua própria galeria.
        // (Geralmente, o dono será um fotógrafo, então esta condição pode ser redundante, mas é clara).
        if ($user->id === $gallery->user_id) {
            return true;
        }

        // Usuário pode ver a galeria se pertencer a algum dos grupos aos quais a galeria está associada.
        // Garante que as relações 'groups' estejam carregadas para ambos.
        if (!$gallery->relationLoaded('groups')) {
            $gallery->load('groups');
        }
        if (!$user->relationLoaded('groups')) {
            $user->load('groups');
        }
        return $user->groups->pluck('id')->intersect($gallery->groups->pluck('id'))->isNotEmpty();
    }

    /**
     * Determine whether the user can create models (criar galerias).
     * Apenas fotógrafos podem criar galerias.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('fotografo');
    }

    /**
     * Determine whether the user can update the model (atualizar galerias).
     * Apenas fotógrafos podem atualizar suas próprias galerias.
     */
    public function update(User $user, Gallery $gallery): bool
    {
        return $user->hasRole('fotografo') && $user->id === $gallery->user_id;
    }

    /**
     * Determine whether the user can delete the model (deletar galerias).
     * Apenas fotógrafos podem deletar suas próprias galerias.
     */
    public function delete(User $user, Gallery $gallery): bool
    {
        return $user->hasRole('fotografo') && $user->id === $gallery->user_id;
    }

    /**
     * Determine whether the user can manage a gallery (e.g., upload images, delete individual images).
     * Apenas fotógrafos podem gerenciar as galerias que lhes pertencem.
     * Esta é uma permissão mais granular para ações dentro de uma galeria.
     */
    public function manageGallery(User $user, Gallery $gallery): bool
    {
        return $user->hasRole('fotografo') && $user->id === $gallery->user_id;
    }
}
