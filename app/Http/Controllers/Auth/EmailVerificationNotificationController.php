<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;    // Tipo de retorno para redirecionamentos
use Illuminate\Http\Request;             // Objeto de requisição HTTP
// O Laravel tem um middleware 'throttle:6,1' para esta rota.
// Isso significa que um usuário pode tentar reenviar o e-mail de verificação
// no máximo 6 vezes em 1 minuto.

class EmailVerificationNotificationController extends Controller
{
    /**
     * Envia uma nova notificação de verificação de e-mail.
     *
     * @param Request $request O objeto de requisição.
     * @return RedirectResponse Redireciona o usuário com um status.
     */
    public function store(Request $request): RedirectResponse
    {
        // Se o e-mail do usuário já estiver verificado, redireciona diretamente para o dashboard.
        if ($request->user()->hasVerifiedEmail()) {
            // Ajustado para usar URL literal '/dashboard' conforme a abordagem "sem Ziggy".
            return redirect()->intended('/dashboard');
        }

        // Se o e-mail não estiver verificado, envia uma nova notificação de verificação.
        $request->user()->sendEmailVerificationNotification();

        // Redireciona de volta para a página anterior com uma mensagem de status.
        return back()->with('status', 'verification-link-sent');
    }
}
