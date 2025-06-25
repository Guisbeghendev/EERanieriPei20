<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate; // Importa a fachada Gate para verificação de permissões
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware personalizado para verificar permissões de usuário baseadas em Gates ou Policies.
 * Este middleware é flexível e pode ser configurado diretamente nas rotas.
 */
class CheckPermission
{
    /**
     * Lida com uma requisição HTTP e verifica se o usuário tem a permissão necessária.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next O próximo middleware na pilha ou o manipulador da rota.
     * @param string $permissionType O tipo de verificação de permissão ('gate' ou 'policy').
     * @param string $permissionName O nome da Gate (ex: 'admin-only', 'create-gallery') ou da ação da Policy (ex: 'update', 'delete').
     * @param string|null $modelParameter Opcional: O nome do parâmetro da rota que representa o modelo (ex: 'gallery', 'user').
     * Usado principalmente para Policies que operam em instâncias de modelo.
     * @return Response A resposta HTTP, que pode ser um redirecionamento, um erro 403, 500 ou a continuação da requisição.
     */
    public function handle(Request $request, Closure $next, string $permissionType, string $permissionName, ?string $modelParameter = null): Response
    {
        // Verifica se o usuário está autenticado. Se não estiver, redireciona para a tela de login.
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user(); // Obtém a instância do usuário autenticado.

        // Lógica para verificar permissões baseada no tipo especificado.
        if ($permissionType === 'gate') {
            // Se o tipo for 'gate', verifica uma Gate específica.
            // Gate::allows() verifica se o usuário tem a permissão definida na Gate.
            if (! Gate::allows($permissionName, $user)) {
                // Se o usuário não tiver permissão, aborta a requisição com um erro 403 (Proibido).
                abort(403, 'Você não tem permissão para acessar esta funcionalidade.');
            }
        } elseif ($permissionType === 'policy') {
            // Se o tipo for 'policy', verifica uma Policy associada a um modelo.
            $modelInstance = null;
            // Tenta obter uma instância do modelo se um 'modelParameter' for fornecido na rota.
            if ($modelParameter && $request->route($modelParameter)) {
                $modelInstance = $request->route($modelParameter);
            }

            // Chama a Policy:
            // - Se um $modelInstance existir (ex: para 'update' em uma galeria),
            //   a Gate verifica Gate::allows('update', $gallery).
            // - Se nenhum $modelInstance for fornecido (ex: para 'create' em uma galeria,
            //   onde a ação não depende de uma instância específica), a Gate verifica
            //   Gate::allows('create', App\Models\Gallery::class), ou passa o próprio usuário
            //   como fallback se nenhum modelo for aplicável ao contexto da Policy.
            if (! Gate::allows($permissionName, $modelInstance ?? $user)) {
                // Se a Policy negar a ação, aborta a requisição com um erro 403.
                abort(403, 'Você não tem permissão para realizar esta ação.');
            }
        } else {
            // Se um 'permissionType' inválido for passado (nem 'gate' nem 'policy'),
            // indica uma configuração de middleware incorreta com um erro 500 (Erro Interno do Servidor).
            abort(500, 'Configuração de permissão inválida.');
        }

        // Se o usuário tiver permissão, a requisição prossegue para o próximo manipulador.
        return $next($request);
    }
}
