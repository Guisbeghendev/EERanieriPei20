<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Image;
use App\Models\Gallery; // Importar Gallery (já existe, mas garantindo)
use Illuminate\Auth\Access\HandlesAuthorization;

class ImagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model (visualizar uma imagem).
     * As regras de visualização de imagem seguem as da sua galeria pai.
     * Para guests em galerias públicas, a lógica é na Gate `view-gallery`.
     */
    public function view(User $user, Image $image): bool
    {
        $gallery = $image->gallery; // Carrega a galeria associada à imagem
        if (!$gallery) {
            return false; // Se a galeria não existe por algum motivo, nega.
        }

        // Fotógrafos sempre podem ver qualquer imagem (para fins de gerenciamento e acesso amplo).
        if ($user->hasRole('fotografo')) {
            return true;
        }

        // O dono da galeria da imagem (que será um fotógrafo) sempre pode ver.
        if ($user->id === $gallery->user_id) {
            return true;
        }

        // Usuário pode ver se pertence a algum dos grupos da galeria da imagem.
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
     * Determine whether the user can create models (criar imagens).
     * Apenas fotógrafos podem criar imagens em galerias que lhes pertencem.
     */
    public function create(User $user, Gallery $gallery): bool
    {
        return $user->hasRole('fotografo') && $user->id === $gallery->user_id;
    }

    /**
     * Determine whether the user can update the model (atualizar imagens).
     * Apenas fotógrafos podem atualizar imagens em galerias que lhes pertencem.
     */
    public function update(User $user, Image $image): bool
    {
        $gallery = $image->gallery; // Carrega a galeria associada à imagem
        if (!$gallery) {
            return false;
        }
        return $user->hasRole('fotografo') && $user->id === $gallery->user_id;
    }

    /**
     * Determine whether the user can delete the model (deletar imagens).
     * Apenas fotógrafos podem deletar imagens em galerias que lhes pertencem.
     */
    public function delete(User $user, Image $image): bool
    {
        $gallery = $image->gallery; // Carrega a galeria associada à imagem
        if (!$gallery) {
            return false;
        }
        return $user->hasRole('fotografo') && $user->id === $gallery->user_id;
    }
}
