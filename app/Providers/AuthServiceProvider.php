<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate; // Importa a fachada Gate para definir e verificar Gates
use App\Models\User;                // Modelo de usuário
use App\Models\Profile;             // Modelo de perfil
use App\Models\Group;               // Modelo de grupo
use App\Models\Gallery;             // Modelo de galeria
use App\Models\Image;               // Modelo de imagem

use App\Policies\UserPolicy;        // Policy para o modelo User
use App\Policies\ProfilePolicy;     // Policy para o modelo Profile
use App\Policies\GroupPolicy;       // Policy para o modelo Group
use App\Policies\GalleryPolicy;     // Policy para o modelo Gallery
use App\Policies\ImagePolicy;       // Policy para o modelo Image

class AuthServiceProvider extends ServiceProvider
{
    /**
     * O mapeamento das Policies para os modelos da aplicação.
     * Define qual classe Policy será usada para autorizar ações em um determinado modelo.
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
     * Este método é onde você define suas Gates personalizadas.
     */
    public function boot(): void
    {
        $this->registerPolicies(); // Registra as policies definidas no array $policies.

        // --- Definição de Gates úteis para o projeto: ---

        /**
         * Gate 'admin-only': Permite acesso somente a usuários com o papel 'admin'.
         * Convenção para funções administrativas gerais.
         */
        Gate::define('admin-only', function (User $user) {
            return $user->hasRole('admin'); // Assume que o modelo User tem um método hasRole() (ex: via Spatie)
        });

        /**
         * Gate 'fotografo-only': Permite acesso somente a usuários com o papel 'fotografo'.
         * Convenção para funções de gerenciamento de conteúdo por fotógrafos.
         */
        Gate::define('fotografo-only', function (User $user) {
            return $user->hasRole('fotografo');
        });

        /**
         * Gate 'edit-profile': Permite que um usuário edite seu próprio perfil,
         * ou que um administrador edite qualquer perfil.
         *
         * @param User $user O usuário autenticado.
         * @param Profile|null $profile O perfil que está sendo editado (pode ser nulo se for a tela de edição do próprio perfil sem um ID explícito).
         */
        Gate::define('edit-profile', function (User $user, ?Profile $profile = null) {
            if ($profile) {
                // Se um perfil específico for fornecido: Admin pode editar qualquer perfil,
                // e um usuário pode editar o perfil que ele mesmo possui.
                return $user->hasRole('admin') || $user->id === $profile->user_id;
            }
            // Se nenhum perfil específico for passado (ex: acessando a tela de 'meu perfil' sem um ID de perfil),
            // a permissão é concedida, pois o usuário estaria editando o próprio perfil implicitamente.
            return true;
        });

        /**
         * Gate 'access-group': Permite que um usuário acesse um grupo se ele for membro do grupo
         * ou se for um administrador.
         *
         * @param User $user O usuário autenticado.
         * @param Group|null $group O grupo que está sendo acessado (pode ser nulo em contextos de listagem).
         */
        Gate::define('access-group', function (User $user, ?Group $group = null) {
            if ($group) {
                // Admin pode acessar qualquer grupo. Usuário comum só pode acessar grupos a que pertence.
                // Carrega a relação 'groups' do usuário se ainda não estiver carregada para evitar N+1 queries.
                if (!$user->relationLoaded('groups')) { $user->load('groups'); }
                return $user->hasRole('admin') || $user->groups->contains($group->id);
            }
            // Se nenhum grupo for passado (ex: contexto de listagem de grupos),
            // a Gate permite se o usuário for admin. A Policy 'viewAny' pode lidar com casos de não-admin.
            return $user->hasRole('admin');
        });

        /**
         * Gate 'create-gallery': Permite que um usuário crie uma nova galeria se ele tiver o papel 'fotografo'.
         * Usa uma função de seta curta (Arrow Function).
         */
        Gate::define('create-gallery', fn(User $user) =>
        $user->hasRole('fotografo')
        );

        /**
         * Gate 'manage-gallery': Permite que um usuário gerencie uma galeria (upload de imagens,
         * edição de detalhes da galeria, etc.) se ele for 'fotografo' E o dono da galeria.
         *
         * @param User $user O usuário autenticado.
         * @param Gallery|null $gallery A instância da galeria a ser gerenciada.
         */
        Gate::define('manage-gallery', function (User $user, ?Gallery $gallery = null) {
            if ($gallery) {
                // Fotógrafos podem gerenciar suas próprias galerias.
                return $user->hasRole('fotografo') && $user->id === $gallery->user_id;
            }
            // Se nenhum modelo de galeria for passado (ex: acesso a uma interface geral
            // de gerenciamento de galerias, antes de selecionar uma específica),
            // a permissão é concedida se o usuário for fotógrafo.
            return $user->hasRole('fotografo');
        });

        /**
         * Gate 'view-public-gallery': Lida com o acesso de guests e usuários autenticados
         * à visualização de galerias individuais, considerando se a galeria é pública ou restrita a grupos.
         *
         * @param User|null $user O usuário autenticado (pode ser nulo para guests).
         * @param Gallery $gallery A instância da galeria a ser visualizada.
         */
        Gate::define('view-public-gallery', function (?User $user, Gallery $gallery) {
            // Carrega a relação 'groups' da galeria se ainda não estiver carregada.
            if (!$gallery->relationLoaded('groups')) { $gallery->load('groups'); }

            // Tenta encontrar o grupo 'público' para verificar se a galeria está associada a ele.
            $publicGroup = Group::where('name', 'público')->first();
            $publicGroupId = $publicGroup ? $publicGroup->id : null;

            // Condição 1: A galeria é pública (associada ao grupo 'público').
            if ($publicGroupId && $gallery->groups->contains($publicGroupId)) {
                return true; // Se for pública, qualquer um (inclusive guests) pode ver.
            }

            // Se não é pública, o usuário DEVE estar autenticado para ter qualquer chance de acessar.
            if (!$user) {
                return false; // Se não estiver autenticado e não for pública, nega o acesso.
            }

            // Garante que as relações 'groups' estejam carregadas para o usuário (se autenticado).
            if (!$user->relationLoaded('groups')) { $user->load('groups'); }

            // Condição 2: O usuário é um 'fotografo' (pode ver qualquer galeria para fins de gerenciamento).
            if ($user->hasRole('fotografo')) {
                return true;
            }

            // Condição 3: O usuário é o dono da galeria (criador).
            if ($gallery->user_id === $user->id) {
                return true;
            }

            // Condição 4: O usuário pertence a qualquer um dos grupos aos quais a galeria está associada.
            $userGroupIds = $user->groups->pluck('id')->toArray();      // IDs dos grupos do usuário.
            $galleryGroupIds = $gallery->groups->pluck('id')->toArray(); // IDs dos grupos da galeria.

            // Verifica se há interseção entre os grupos do usuário e os grupos da galeria.
            if (array_intersect($userGroupIds, $galleryGroupIds)) {
                return true;
            }

            // Se nenhuma das condições acima for satisfeita, o acesso é negado.
            return false;
        });

        /**
         * Gate 'manage-image': Permite que um usuário gerencie uma imagem (criar, atualizar, deletar)
         * se ele for 'fotografo' E o dono da galeria à qual a imagem pertence.
         *
         * @param User $user O usuário autenticado.
         * @param Image|null $image A instância da imagem a ser gerenciada (pode ser nulo para ações como 'create').
         */
        Gate::define('manage-image', function (User $user, ?Image $image = null) {
            if ($image) {
                // Se uma imagem específica for fornecida, obtém a galeria associada.
                $gallery = $image->gallery;
                if (!$gallery) return false; // Se a galeria não existe, nega.
                // Fotógrafos gerenciam imagens que pertencem às suas próprias galerias.
                return $user->hasRole('fotografo') && $user->id === $gallery->user_id;
            }
            // Se nenhum modelo de imagem for passado (ex: para acessar uma interface geral de upload de imagens),
            // a permissão é concedida se o usuário for fotógrafo.
            return $user->hasRole('fotografo');
        });
    }
}
