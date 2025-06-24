<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
// use Illuminate\Support\Facades\Auth; // Comentado, pois não é usado nas rotas ativas

// Removendo ou comentando importações de Controllers de autenticação e outros que não são usados nas rotas ativas.
// use App\Http\Controllers\Auth\AuthenticatedSessionController;
// use App\Http\Controllers\Auth\RegisteredUserController;
// use App\Http\Controllers\Auth\PasswordResetLinkController;
// use App\Http\Controllers\Auth\NewPasswordController;

// --- ROTAS PÚBLICAS ---
Route::get('/', function () {
    return Inertia::render('Home');
});

/*
// Outras rotas públicas (comentadas por enquanto)
Route::get('/sobre-a-escola', function () {
    return Inertia::render('Sobre/SobreEscola');
})->name('sobre-a-escola');

Route::get('/coralranieri', function () {
    return Inertia::render('Coral/CoralRanieri');
})->name('coral-ranieri');

Route::get('/gremio', function () {
    return Inertia::render('Gremio/Gremio');
})->name('gremio');

Route::get('/brincandodialogando', function () {
    return Inertia::render('BrincandoDialogando/BrincandoDialogando');
})->name('brincando-dialogando');

Route::get('/simoninhanacozinha', function () {
    return Inertia::render('Simoninhanacozinha/Simoninhanacozinha');
})->name('simoninhanacozinha');
*/


/*
// --- ROTAS DE AUTENTICAÇÃO (Breeze Padrão - comentadas por enquanto) ---
// Importações para os controllers de autenticação seriam necessárias aqui se estas rotas estivessem ativas.
// use App\Http\Controllers\Auth\AuthenticatedSessionController;
// use App\Http\Controllers\Auth\RegisteredUserController;
// use App\Http\Controllers\Auth\PasswordResetLinkController;
// use App\Http\Controllers\Auth\NewPasswordController;

Route::middleware('guest')->group(function () {
    // Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    // Route::post('register', [RegisteredUserController::class, 'store']);

    // Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    // Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    // Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    // Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    // Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// --- ROTA DE LOGOUT (comentada por enquanto) ---
Route::middleware('auth')->group(function () {
    // Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
*/
