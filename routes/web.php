<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
// Importações de Controllers de autenticação
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController; // Adicionado a importação correta
// Importar o DashboardController
use App\Http\Controllers\DashboardController;

// --- ROTAS PÚBLICAS GERAIS (acesso livre para todos, inclusive visitantes não autenticados) ---
// Rota principal da aplicação.
Route::get('/', function () {
    return Inertia::render('Home');
});

// Rotas para páginas de conteúdo público da escola.
Route::get('/sobre-a-escola', function () {
    return Inertia::render('SobreEscola');
})->name('sobre-a-escola');

Route::get('/coral-ranieri', function () {
    return Inertia::render('CoralRanieri');
})->name('coral-ranieri');

Route::get('/gremio', function () {
    return Inertia::render('Gremio');
})->name('gremio');

Route::get('/brincando-dialogando', function () {
    return Inertia::render('BrincandoDialogando');
})->name('brincando-dialogando');

Route::get('/simoninhanacozinha', function () {
    return Inertia::render('SimoninhaNaCozinha');
})->name('simoninhanacozinha');

// Rota para exibir uma galeria pública específica (o {gallery} será o ID da galeria)
Route::get('/galleries/{gallery}', function () {
    return Inertia::render('Galleries/Show', [
        'gallery' => null,
    ]);
})->name('public.galleries.show');

// --- ROTAS DE AUTENTICAÇÃO (Laravel Breeze) ---
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

// --- ROTAS AUTENTICADAS ---
Route::middleware('auth')->group(function () {
    // Rota de Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Rotas de verificação de e-mail
    // Ajustado para usar o controller invokable corretamente
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Esta rota para reenviar o e-mail agora aponta para o método 'store' do EmailVerificationNotificationController
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Rotas para confirmar senha
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);


    // Rota do Dashboard para qualquer usuário logado (Middleware 'verified' garante e-mail verificado)
    Route::get('/dashboard', DashboardController::class)
        ->middleware(['verified'])
        ->name('dashboard');

    // Rota para o perfil do usuário (ainda placeholder)
    Route::get('/profile', function () {
        return Inertia::render('Profile/Edit');
    })->name('profile.edit');

    // Rotas para a listagem geral de galerias (ainda placeholder)
    Route::get('/galleries', function () {
        return Inertia::render('Galleries/Index');
    })->name('galleries.index');

    // --- ROTAS DE ADMIN (protegidas pelo middleware de role) ---
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return Inertia::render('Admin/Dashboard');
        })->name('admin.dashboard');
    });

    // --- ROTAS DE FOTÓGRAFO (protegidas pelo middleware de role) ---
    Route::middleware('role:fotografo')->group(function () {
        Route::get('/fotografo/dashboard', function () {
            return Inertia::render('Fotografo/Dashboard');
        })->name('fotografo.dashboard');
    });
});
