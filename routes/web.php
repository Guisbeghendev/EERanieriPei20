<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
// Importações necessárias para os controllers de autenticação do Breeze
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

// --- ROTAS PÚBLICAS GERAIS (acesso livre para todos, inclusive visitantes não autenticados) ---
// Rota principal da aplicação.
Route::get('/', function () {
    return Inertia::render('Home');
});

// Rotas para páginas de conteúdo público da escola, acessíveis a qualquer visitante.
Route::get('/sobre-a-escola', function () {
    // Será necessário criar o arquivo resources/js/Pages/SobreEscola.vue
    return Inertia::render('SobreEscola');
})->name('sobre-a-escola');

Route::get('/coral-ranieri', function () {
    // Será necessário criar o arquivo resources/js/Pages/CoralRanieri.vue
    return Inertia::render('CoralRanieri');
})->name('coral-ranieri');

Route::get('/gremio', function () {
    // Será necessário criar o arquivo resources/js/Pages/Gremio.vue
    return Inertia::render('Gremio');
})->name('gremio');

Route::get('/brincando-dialogando', function () {
    // Será necessário criar o arquivo resources/js/Pages/BrincandoDialogando.vue
    return Inertia::render('BrincandoDialogando');
})->name('brincando-dialogando');

Route::get('/simoninhanacozinha', function () {
    // Será necessário criar o arquivo resources/js/Pages/SimoninhaNaCozinha.vue
    return Inertia::render('SimoninhaNaCozinha');
})->name('simoninhanacozinha');

// --- ROTAS DE AUTENTICAÇÃO (Laravel Breeze) ---
// Estas rotas são gerenciadas pelo Breeze e permitem o registro, login e redefinição de senha.
// O middleware 'guest' garante que apenas usuários não autenticados possam acessá-las.
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

// --- ROTAS PROTEGIDAS (acessíveis apenas para usuários autenticados) ---
// O middleware 'auth' garante que o usuário esteja logado para acessar estas rotas.
Route::middleware('auth')->group(function () {
    // Rota para realizar o logout do usuário.
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // Dashboard padrão para usuários autenticados que não são admin nem fotógrafo.
    Route::get('/dashboard', function () {
        // Será necessário criar o arquivo resources/js/Pages/Dashboard.vue
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Rota para a página de edição do perfil do usuário logado.
    Route::get('/profile', function () {
        // Será necessário criar o arquivo resources/js/Pages/Profile/Edit.vue
        // (e a pasta Profile dentro de Pages)
        return Inertia::render('Profile/Edit');
    })->name('profile.edit');

    // Rota para a listagem de galerias, acessível a qualquer usuário logado.
    Route::get('/galleries', function () {
        // Será necessário criar o arquivo resources/js/Pages/Galleries/Index.vue
        // (e a pasta Galleries dentro de Pages)
        return Inertia::render('Galleries/Index');
    })->name('galleries.index');

    // --- ROTAS DE ADMIN (protegidas pela Gate 'admin-only') ---
    // Apenas usuários com a role 'admin' podem acessar as rotas dentro deste grupo.
    Route::middleware('can:admin-only')->group(function () {
        Route::get('/admin/dashboard', function () {
            // Será necessário criar o arquivo resources/js/Pages/Admin/Dashboard.vue
            // (e a pasta Admin dentro de Pages)
            return Inertia::render('Admin/Dashboard');
        })->name('admin.dashboard');

        // Futuras rotas de gerenciamento de usuários, grupos, etc. para administradores virão aqui.
        // Ex: Route::resource('admin/users', AdminUserController::class);
    });

    // --- ROTAS DE FOTÓGRAFO (protegidas pela Gate 'fotografo-only') ---
    // Apenas usuários com a role 'fotografo' podem acessar as rotas dentro deste grupo.
    Route::middleware('can:fotografo-only')->group(function () {
        Route::get('/fotografo/dashboard', function () {
            // Será necessário criar o arquivo resources/js/Pages/Fotografo/Dashboard.vue
            // (e a pasta Fotografo dentro de Pages)
            return Inertia::render('Fotografo/Dashboard');
        })->name('fotografo.dashboard');

        // Futuras rotas de gerenciamento de galerias/imagens específicas para fotógrafos virão aqui.
        // Ex: Route::resource('fotografo/galleries', FotografoGalleryController::class);
    });
});
