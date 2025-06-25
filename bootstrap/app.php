<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandleInertiaRequests;   // Middleware do Inertia.js
use App\Http\Middleware\CheckPermission;        // Importação do seu middleware personalizado de permissões

/**
 * Configura e cria a instância da aplicação Laravel.
 * Este é o ponto de partida para a configuração do framework.
 */
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
    // Define o caminho para o arquivo de rotas web.
        web: __DIR__.'/../routes/web.php',
        // Define o caminho para o arquivo de comandos do console.
        commands: __DIR__.'/../routes/console.php',
        // Define uma rota de health check (verificação de saúde da aplicação).
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Registra os middlewares que serão aplicados ao grupo 'web'.
        // Esses middlewares são executados em todas as requisições que usam o roteamento web.
        $middleware->web(append: [
            // Middleware essencial para proteção contra ataques CSRF (Cross-Site Request Forgery).
            // Ele verifica a validade do token CSRF em formulários e requisições POST/PUT/DELETE,
            // garantindo que as requisições são originadas da sua própria aplicação.
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,

            // Middleware do Inertia.js. Ele é responsável por interceptar requisições do Inertia
            // e renderizar as páginas Vue.js de forma apropriada, injetando os dados do servidor.
            HandleInertiaRequests::class,

            // Middleware para otimização de performance.
            // Adiciona cabeçalhos Link (rel="preload", rel="prefetch") para recursos (assets)
            // que o navegador deve pré-carregar, o que pode melhorar significativamente
            // a velocidade de carregamento percebida da sua aplicação.
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Registra aliases de middleware.
        // Aliases permitem que você use nomes curtos e legíveis para referenciar middlewares
        // nas suas definições de rota (ex: Route::middleware('auth')->get(...)).
        $middleware->alias([
            // Exemplo de aliases comuns que o Laravel Breeze/Fortify já pode ter, ou que você pode adicionar:
            // 'auth' => \App\Http\Middleware\Authenticate::class, // Middleware de autenticação padrão
            // 'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class, // Middleware para e-mail verificado
            // 'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class, // Middleware para controle de taxa de requisições
            // Se você usa o Spatie/Laravel-Permission, estes aliases seriam configurados aqui:
            // 'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
            // 'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
            // 'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,

            // SEU NOVO MIDDLEWARE PERSONALIZADO: 'check.permission'
            // Este alias permite que você use `->middleware('check.permission:gate,admin-only')`
            // ou `->middleware('check.permission:policy,update,gallery')` nas suas rotas.
            'check.permission' => CheckPermission::class,
        ]);

        // Se você tivesse middlewares específicos para API, eles seriam configurados aqui.
        // $middleware->api(append: [
        //     // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        //     // 'throttle:api',
        //     // \Illuminate\Routing\Middleware\SubstituteBindings::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Esta seção é para configurar como seu aplicativo lida com exceções (erros).
        // Você pode registrar handlers para reportar exceções (ex: para Sentry, Bugsnag)
        // ou para renderizar respostas personalizadas para diferentes tipos de erros.
    })->create(); // Finaliza a configuração e cria a instância da aplicação.
