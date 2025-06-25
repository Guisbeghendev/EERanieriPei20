<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;    // Tipo de retorno para redirecionamentos
use Illuminate\Http\Request;             // Objeto de requisição HTTP
use Illuminate\Support\Facades\Password; // Facade para gerenciar o processo de redefinição de senha
use Illuminate\Validation\ValidationException; // Exceção para erros de validação
use Inertia\Inertia;                     // Classe Inertia para renderizar páginas Vue
use Inertia\Response;                    // Tipo de retorno para respostas Inertia

class PasswordResetLinkController extends Controller
{
    /**
     * Exibe a view de solicitação de link de redefinição de senha.
     * Retorna a página 'Auth/ForgotPassword' para o frontend.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            // 'status' é uma mensagem de sessão que pode ser exibida
            // (ex: "Enviamos o link de redefinição de senha para seu e-mail!").
            'status' => session('status'),
        ]);
    }

    /**
     * Lida com uma requisição de envio de link de redefinição de senha.
     *
     * @param Request $request O objeto de requisição contendo o e-mail.
     * @return RedirectResponse Redireciona o usuário de volta com um status ou lança exceção.
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Valida se o campo 'email' é obrigatório e um formato de e-mail válido.
        $request->validate([
            'email' => 'required|email',
        ]);

        // Envia o link de redefinição de senha para o e-mail fornecido.
        // O método `sendResetLink` do Laravel lida com a geração do token,
        // o envio do e-mail e o registro do token no banco de dados.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Se o link foi enviado com sucesso, redireciona de volta com uma mensagem de status.
        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        // Se houver uma falha (ex: e-mail não encontrado), lança uma exceção de validação
        // com a mensagem de erro apropriada.
        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
