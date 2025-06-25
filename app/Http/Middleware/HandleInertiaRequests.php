<?php

// Este middleware é responsável por compartilhar dados do backend com o frontend Inertia.

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\User; // Garante que o modelo User esteja importado

class HandleInertiaRequests extends Middleware
{
    /**
     * O template raiz que é carregado na primeira visita à página.
     * Geralmente 'app' corresponde a resources/views/app.blade.php.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determina a versão atual dos assets.
     * Ajuda o Inertia a fazer cache busting dos assets do frontend.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define as props que são compartilhadas por padrão com todas as páginas Inertia.
     * Aqui compartilhamos informações do usuário autenticado, suas roles e avatar.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user(); // Obtém o usuário autenticado, se houver.

        // Garante que as relações essenciais (roles, avatar) sejam carregadas.
        // loadMissing evita recarregar se já estiverem presentes, otimizando.
        if ($user) {
            $user->loadMissing(['roles', 'avatar']);
        }

        return array_merge(parent::share($request), [
            // Compartilha o status de autenticação e os dados do usuário.
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    // Mapeia as roles do usuário para um formato simples (id e name) para o frontend.
                    'roles' => $user->roles->map(fn ($role) => [
                        'id' => $role->id,
                        'name' => $role->name,
                    ])->toArray(), // Garante que a coleção seja convertida para array.
                    // Compartilha a URL do avatar do usuário, acessando o accessor 'url' do modelo Avatar.
                    'avatar' => $user->avatar ? [
                        'url' => $user->avatar->url,
                    ] : null,
                ] : null, // Se não houver usuário, 'user' será null.
            ],
        ]);
    }
}
