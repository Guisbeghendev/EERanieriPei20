<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;               // Evento disparado após a verificação de e-mail
use Illuminate\Foundation\Auth\EmailVerificationRequest; // Requisição específica para verificação de e-mail
use Illuminate\Http\RedirectResponse;              // Tipo de retorno para redirecionamentos
use App\Providers\RouteServiceProvider;            // Provedor de serviços para constantes de rota

class VerifyEmailController extends Controller
{
    /**
     * Marca o endereço de e-mail do usuário autenticado como verificado.
     * Este é um método invokable, o que significa que o controlador em si pode ser chamado como uma função.
     *
     * @param EmailVerificationRequest $request A requisição de verificação de e-mail.
     * @return RedirectResponse Redireciona o usuário após a verificação.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Se o e-mail do usuário já estiver verificado, redireciona diretamente.
        if ($request->user()->hasVerifiedEmail()) {
            // Redireciona para o dashboard com um parâmetro 'verified=1' para feedback no frontend.
            // Utiliza a constante HOME do RouteServiceProvider para a URL de destino.
            return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
        }

        // Se o e-mail não estiver verificado e puder ser marcado como verificado, faz isso.
        if ($request->user()->markEmailAsVerified()) {
            // Dispara o evento 'Verified' após a verificação bem-sucedida.
            event(new Verified($request->user()));
        }

        // Redireciona o usuário para o dashboard após a verificação (ou se já estiver verificado).
        // Adiciona '?verified=1' para que o frontend saiba que a verificação ocorreu.
        return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
    }
}
