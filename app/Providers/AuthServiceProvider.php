<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Profile;
use App\Models\Group;
use App\Models\Gallery;
use App\Models\Image;

use App\Policies\UserPolicy;
use App\Policies\ProfilePolicy;
use App\Policies\GroupPolicy;
use App\Policies\GalleryPolicy;
use App\Policies\ImagePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Os mapeamentos de políticas para a aplicação.
     * Associa um modelo à sua respectiva Policy.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Profile::class => ProfilePolicy::class,
        Group::class => GroupPolicy::class,
        Gallery::class => GalleryPolicy::class,
        Image::class => ImagePolicy::class,
    ];

    /**
     * Registra quaisquer serviços de autenticação / autorização.
     *
     * @return void
     */
    public function boot(): void
    {
        // Registra as políticas definidas no array $policies.
        $this->registerPolicies();

        // --- Variáveis de suporte para Gates (otimização) ---
        // Carrega o ID do grupo 'free' uma única vez para uso nas Gates, evitando consultas repetidas.
        $freeGroupId = null;
        $freeGroup = Group::where('name', 'free')->first();
        if ($freeGroup) {
            $freeGroupId = $freeGroup->id;
        }

        // --- Gates úteis para o projeto: ---

        /**
         * Gate: 'admin-only'
         * Permite acesso apenas a usuários com a role 'admin'.
         * Usada para funcionalidades administrativas gerais.
         */
        Gate::define('admin-only', function (User $user) {
            return $user->hasRole('admin');
        });

        /**
         * Gate: 'fotografo-only'
         * Permite acesso apenas a usuários com a role 'fotografo'.
         * Usada para funcionalidades de gerenciamento de conteúdo por fotógrafos.
         */
        Gate::define('fotografo-only', function (User $user) {
            return $user->hasRole('fotografo');
        });

        /**
         * Gate: 'edit-profile'
         * Permite que um usuário edite seu próprio perfil ou que um admin edite qualquer perfil.
         *
         * @param \App\Models\User $user O usuário autenticado.
         * @param \App\Models\Profile|null $profile O perfil a ser editado (opcional, para casos onde o perfil não é conhecido).
         * @return bool
         */
        Gate::define('edit-profile', function (User $user, ?Profile $profile = null) {
            if ($profile) {
                // Admin pode editar qualquer perfil. Usuário pode editar o próprio.
                return $user->hasRole('admin') || $user->id === $profile->user_id;
            }
            // Se nenhum perfil específico for passado (ex: para a tela de edição do próprio perfil do usuário logado),
            // permite, pois o usuário sempre pode tentar editar seu próprio perfil.
            return true;
        });

        /**
         * Gate: 'access-group'
         * Permite que um usuário acesse um grupo específico ou a funcionalidade de grupos em geral.
         *
         * @param \App\Models\User $user O usuário autenticado.
         * @param \App\Models\Group|null $group O grupo a ser acessado (opcional).
         * @return bool
         */
        Gate::define('access-group', function (User $user, ?Group $group = null) {
            // Se não há usuário logado, não pode acessar grupo (grupos são para usuários autenticados).
            if (!$user) {
                return false;
            }
            if ($group) {
                // Admin pode acessar qualquer grupo. Usuário pode acessar grupos a que pertence.
                if (!$user->relationLoaded('groups')) {
                    $user->load('groups');
                }
                return $user->hasRole('admin') || $user->groups->contains($group->id);
            }
            // Se nenhum grupo for passado (ex: acesso a uma interface geral de listagem/gerenciamento de grupos),
            // permite apenas para administradores, para ser mais restritivo por padrão.
            return $user->hasRole('admin');
        });

        /**
         * Gate: 'create-gallery'
         * Permite que um usuário crie uma nova galeria.
         *
         * @param \App\Models\User $user O usuário autenticado.
         * @return bool
         */
        Gate::define('create-gallery', fn(User $user) =>
            // Apenas usuários logados que são fotógrafos podem criar galerias.
            $user && $user->hasRole('fotografo')
        );

        /**
         * Gate: 'manage-gallery'
         * Permite que um usuário gerencie (upload de imagens, etc.) uma galeria específica.
         *
         * @param \App\Models\User $user O usuário autenticado.
         * @param \App\Models\Gallery|null $gallery A galeria a ser gerenciada (opcional).
         * @return bool
         */
        Gate::define('manage-gallery', function (User $user, ?Gallery $gallery = null) {
            // Se não há usuário logado, não pode gerenciar galeria.
            if (!$user) {
                return false;
            }
            if ($gallery) {
                // Fotógrafos gerenciam suas próprias galerias.
                return $user->hasRole('fotografo') && $user->id === $gallery->user_id;
            }
            // Se nenhum modelo de galeria for passado (ex: acesso a uma interface geral de gerenciamento),
            // permite apenas para fotógrafos.
            return $user->hasRole('fotografo');
        });

        /**
         * GATE: 'view-gallery'
         * Controla o acesso de visualização a GALERIAS INDIVIDUAIS.
         * Esta Gate é complexa e lida com acesso público (guests) e autenticado.
         *
         * @param \App\Models\User|null $user O usuário autenticado ou null se for guest.
         * @param \App\Models\Gallery $gallery A galeria a ser visualizada.
         * @return bool
         */
        Gate::define('view-gallery', function (?User $user, Gallery $gallery) use ($freeGroupId) {
            // Garante que as relações 'groups' estejam carregadas para a galeria
            if (!$gallery->relationLoaded('groups')) {
                $gallery->load('groups');
            }

            // Condição 1: A galeria é "pública" (associada ao grupo 'free').
            // Se a galeria está associada ao grupo 'free', qualquer um (guest ou logado) pode vê-la.
            if ($freeGroupId && $gallery->groups->contains($freeGroupId)) {
                return true;
            }

            // Se a galeria NÃO é pública E não há usuário logado, nega o acesso.
            // Esta checagem vem DEPOIS da checagem de público.
            if (!$user) {
                return false;
            }

            // A partir daqui, o usuário está AUTENTICADO.

            // Garante que as relações 'groups' estejam carregadas para o usuário autenticado.
            if (!$user->relationLoaded('groups')) {
                $user->load('groups');
            }

            // Condição 2: Usuário logado é fotógrafo (pode ver qualquer galeria para fins de gerenciamento e acesso amplo).
            if ($user->hasRole('fotografo')) {
                return true;
            }

            // Condição 3: Usuário logado é o dono da galeria.
            if ($gallery->user_id === $user->id) {
                return true;
            }

            // Condição 4: Usuário logado pertence a qualquer um dos grupos da galeria (excluindo 'free', já tratado acima).
            $userGroupIds = $user->groups->pluck('id')->toArray();
            $galleryGroupIds = $gallery->groups->pluck('id')->toArray();

            return (bool) array_intersect($userGroupIds, $galleryGroupIds);
        });

        /**
         * Gate: 'manage-image'
         * Permite que um usuário gerencie (criar, atualizar, deletar) imagens em uma galeria.
         *
         * @param \App\Models\User $user O usuário autenticado.
         * @param \App\Models\Image|null $image A imagem a ser gerenciada (opcional, para ações que exigem uma imagem específica).
         * @param \App\Models\Gallery|null $gallery A galeria à qual a imagem pertence (opcional, para criação).
         * @return bool
         */
        Gate::define('manage-image', function (User $user, ?Image $image = null, ?Gallery $gallery = null) {
            // Se não há usuário logado, não pode gerenciar imagem.
            if (!$user) {
                return false;
            }

            // Para operações que envolvem uma imagem específica (update, delete)
            if ($image) {
                $parentGallery = $image->gallery; // Carrega a galeria associada à imagem
                if (!$parentGallery) return false; // Se a galeria não existe, nega o acesso.
                // Fotógrafos gerenciam imagens em suas próprias galerias.
                return $user->hasRole('fotografo') && $user->id === $parentGallery->user_id;
            }

            // Para operações de criação de imagem, a galeria pai deve ser fornecida via segundo argumento
            // ou assumida de algum contexto (ex: rota /galleries/{gallery}/images/create).
            // Se a galeria é fornecida via $gallery (para criação), usa ela.
            if ($gallery) {
                return $user->hasRole('fotografo') && $user->id === $gallery->user_id;
            }

            // Se nenhum modelo específico (imagem ou galeria) for passado,
            // e a Gate for usada para verificar permissão geral de "gerenciar imagens",
            // permite apenas se o usuário for fotógrafo.
            return $user->hasRole('fotografo');
        });
    }
}
