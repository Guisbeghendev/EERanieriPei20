<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;    // Tipo de retorno para redirecionamentos
use Illuminate\Http\Request;             // Objeto de requisição HTTP
use Inertia\Inertia;                     // Classe Inertia para renderizar páginas Vue
use Inertia\Response;                    // Tipo de retorno para respostas Inertia
use App\Providers\RouteServiceProvider;   // Provedor de serviços para constantes de rota

class EmailVerificationPromptController extends Controller
{
    /**
     * Exibe o prompt de verificação de e-mail.
     * Este método é invokable, o que significa que o controlador pode ser chamado como uma função.
     *
     * @param Request $request O objeto de requisição.
     * @return RedirectResponse|Response Retorna um redirecionamento se o e-mail estiver verificado,
     * ou a página de verificação caso contrário.
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        // Verifica se o e-mail do usuário autenticado já foi verificado.
        // Se sim, redireciona para o dashboard.
        // CORRIGIDO: Usando a URL literal para consistência com a abordagem "sem Ziggy".
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(RouteServiceProvider::HOME)
            : Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
    }
}
