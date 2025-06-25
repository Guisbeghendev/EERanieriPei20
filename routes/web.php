<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
// Importações de Controllers de autenticação (verifique se todas estão presentes)
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController; // Controller invokable para verificar link
use App\Http\Controllers\Auth\ConfirmablePasswordController; // Controller para confirmação de senha
use App\Http\Controllers\Auth\EmailVerificationNotificationController; // Controller para reenviar notificação
use App\Http\Controllers\Auth\EmailVerificationPromptController; // Controller invokable para exibir prompt de verificação
// Importar o DashboardController
use App\Http\Controllers\DashboardController;

// --- ROTAS PÚBLICAS GERAIS (acesso livre para todos, inclusive visitantes não autenticados) ---
// Rota principal da aplicação.
Route::get('/', function () {
    return Inertia::render('Home');
});

// Outras rotas públicas para páginas de conteúdo (ajustadas para pastas, se for o caso)
Route::get('/sobre-a-escola', function () {
    return Inertia::render('Sobre/SobreEscola');
})->name('sobre-a-escola');

Route::get('/gremio', function () {
    return Inertia::render('Gremio/Gremio');
})->name('gremio');

Route::get('/brincando-dialogando', function () {
    return Inertia::render('BrincandoDialogando/BrincandoDialogando');
})->name('brincando-dialogando');

Route::get('/simoninhanacozinha', function () {
    return Inertia::render('Simoninhanacozinha/Simoninhanacozinha');
})->name('simoninhanacozinha');

Route::get('/coral-ranieri', function () {
    return Inertia::render('Coral/CoralRanieri');
})->name('coral-ranieri');

// Rota para exibir uma galeria pública específica (necessário para links do Dashboard)
Route::get('/galleries/{gallery}', function () {
    // Este é um placeholder. O ideal é ter um controller para buscar a galeria real.
    return Inertia::render('Galleries/Show', [
        'gallery' => null, // Placeholder: dados reais viriam de um controller.
    ]);
})->name('public.galleries.show');


// --- ROTAS DE AUTENTICAÇÃO (Laravel Breeze Padrão) ---
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// --- ROTAS AUTENTICADAS (COM LOGOUT E VERIFICAÇÃO DE E-MAIL) ---
Route::middleware('auth')->group(function () {
    // Rota de Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Rotas de verificação de e-mail (Laravel Breeze padrão)
    // CORREÇÃO: Usando EmailVerificationPromptController para exibir o prompt.
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->middleware('throttle:6,1')
        ->name('verification.notice');

    // CORREÇÃO: Usando VerifyEmailController para a ação de verificar o e-mail via link.
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // CORREÇÃO: Usando EmailVerificationNotificationController para reenviar o e-mail de verificação.
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Rotas para confirmar senha
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Rota do Dashboard (agora apontando para o seu DashboardController invokable)
    // CORREÇÃO: Apontando para DashboardController::class.
    Route::get('/dashboard', DashboardController::class)
        ->middleware(['verified'])
        ->name('dashboard');

    // Rota para o perfil do usuário (ainda pode ser um placeholder)
    Route::get('/profile', function () {
        return Inertia::render('Profile/Edit'); // Usando Edit.vue como a tela de perfil
    })->name('profile.edit');

    // Rota para galerias acessível apenas por usuários autenticados
    Route::get('/galleries', function () {
        return Inertia::render('Galleries/Index');
    })->name('galleries.index');

    // --- ROTAS ESPECÍFICAS DE PERFIL (admin e fotógrafo) ---
    // Certifique-se de que o middleware 'role' esteja configurado (ex: com Spatie Permissions)
    Route::middleware('role:fotografo')->group(function () {
        Route::get('/fotografo/dashboard', function () {
            return Inertia::render('Fotografo/Dashboard');
        })->name('fotografo.dashboard');
        // Outras rotas do fotógrafo...
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return Inertia::render('Admin/Dashboard');
        })->name('admin.dashboard');
        // Outras rotas do administrador...
    });
});
