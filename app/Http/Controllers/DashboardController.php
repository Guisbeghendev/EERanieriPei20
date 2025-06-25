<?php

namespace App\Http\Controllers;

use App\Models\Group; // Importa o modelo Group
use App\Models\User;  // Importa o modelo User
use Illuminate\Http\Request; // Objeto de requisição HTTP
use Inertia\Inertia;         // Classe Inertia para renderizar páginas Vue
use Illuminate\Support\Facades\Auth; // Facade para gerenciar autenticação

class DashboardController extends Controller
{
    /**
     * Lida com a requisição de entrada para exibir o dashboard.
     * Este é um controller invokable, ou seja, pode ser chamado diretamente na rota.
     *
     * @param  \Illuminate\Http\Request  $request O objeto de requisição.
     * @return \Inertia\Response Retorna uma resposta Inertia contendo os dados para o dashboard.
     */
    public function __invoke(Request $request)
    {
        // Obtém o usuário autenticado.
        $user = Auth::user();

        // Carrega as relações necessárias para o usuário:
        // 'roles': Os papéis do usuário (admin, fotografo, etc.), essenciais para permissões no frontend.
        // 'profile': O perfil do usuário, que contém informações adicionais.
        // 'avatar': A imagem de avatar do usuário, para exibição no frontend (ex: na Navbar).
        $user->load('roles', 'profile', 'avatar');

        // Carrega os grupos aos quais o usuário pertence, juntamente com as últimas galerias de cada grupo.
        $userGroupsWithLatestGalleries = $user->groups()
            // Carrega as galerias de cada grupo.
            ->with(['galleries' => function ($query) {
                // Eager load a primeira imagem de cada galeria para a miniatura (preview).
                $query->with(['images' => function ($subQuery) {
                    $subQuery->orderBy('id')->take(1); // Pega apenas a primeira imagem pela ID.
                }])
                    // Ordena as galerias pelas mais recentes (data do evento).
                    ->latest('event_date')
                    // Limita a 3 galerias por grupo para exibir no dashboard.
                    ->take(3);
                // A lógica para excluir galerias do grupo 'público' foi removida,
                // permitindo que todas as galerias dos grupos do usuário sejam exibidas.
            }])
            // Garante que apenas grupos que possuem galerias sejam carregados, ordenando pelas galerias mais recentes.
            ->whereHas('galleries', function ($query) {
                $query->latest('event_date');
            })
            ->get(); // Executa a consulta e retorna a coleção de grupos.

        // Retorna a view 'Dashboard' do Inertia, passando os dados necessários para o frontend.
        return Inertia::render('Dashboard', [
            // Passa os dados do usuário autenticado para o frontend, incluindo suas relações carregadas.
            'auth' => [
                'user' => $user->toArray(),
            ],
            // Passa a coleção de grupos do usuário com as últimas galerias carregadas.
            'userGroupsWithLatestGalleries' => $userGroupsWithLatestGalleries,
        ]);
    }
}
