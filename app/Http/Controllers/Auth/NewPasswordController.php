<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset; // Evento disparado após a redefinição de senha
use Illuminate\Http\RedirectResponse;      // Tipo de retorno para redirecionamentos
use Illuminate\Http\Request;               // Objeto de requisição HTTP
use Illuminate\Support\Facades\Hash;       // Facade para hash de senhas
use Illuminate\Support\Facades\Password;   // Facade para gerenciar o processo de redefinição de senha
use Illuminate\Support\Str;                // Helper para strings (usado para gerar remember_token)
use Illuminate\Validation\Rules;           // Regras de validação do Laravel
use Illuminate\Validation\ValidationException; // Exceção para erros de validação
use Inertia\Inertia;                       // Classe Inertia para renderizar páginas Vue
use Inertia\Response;                      // Tipo de retorno para respostas Inertia

class NewPasswordController extends Controller
{
    /**
     * Exibe a view de redefinição de senha (formulário para nova senha).
     * Esta rota é acessada via o link enviado por e-mail.
     *
     * @param Request $request O objeto de requisição, contendo o e-mail e o token na URL.
     * @return Response Retorna a página 'Auth/ResetPassword' para o frontend.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/ResetPassword', [
            'email' => $request->email,         // O e-mail do usuário, passado como prop.
            'token' => $request->route('token'), // O token de redefinição, obtido da rota, passado como prop.
        ]);
    }

    /**
     * Lida com uma requisição de nova senha (processa o formulário de redefinição).
     *
     * @param Request $request O objeto de requisição contendo a nova senha e token.
     * @return RedirectResponse Redireciona o usuário para a página de login com um status.
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Valida os dados da requisição: token, email e as novas senhas (obrigatórias, confirmadas e com regras padrão).
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Tenta redefinir a senha do usuário.
        // O método `Password::reset` recebe os dados e um callback para atualizar o usuário.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                // Atualiza a senha do usuário e gera um novo remember_token por segurança.
                $user->forceFill([
                    'password' => Hash::make($request->password), // Hash da nova senha.
                    'remember_token' => Str::random(60),        // Gera um novo token para lembrar sessão.
                ])->save();

                // Dispara o evento 'PasswordReset' após a redefinição bem-sucedida.
                event(new PasswordReset($user));
            }
        );

        // Se a redefinição de senha foi bem-sucedida, redireciona para a página de login
        // com uma mensagem de status.
        if ($status == Password::PASSWORD_RESET) {
            // Alterado para URL literal para consistência com a abordagem "sem Ziggy".
            return redirect('/login')->with('status', __($status));
        }

        // Se a redefinição de senha falhar, lança uma exceção de validação.
        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
