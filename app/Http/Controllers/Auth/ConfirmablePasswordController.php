<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;    // Tipo de retorno para redirecionamentos
use Illuminate\Http\Request;             // Objeto de requisição HTTP
use Illuminate\Support\Facades\Auth;     // Facade para gerenciar autenticação
use Illuminate\Validation\ValidationException; // Exceção para erros de validação
use Inertia\Inertia;                     // Classe Inertia para renderizar páginas Vue
use Inertia\Response;                    // Tipo de retorno para respostas Inertia

class ConfirmablePasswordController extends Controller
{
    /**
     * Exibe a view de confirmação de senha.
     * Esta view é usada para reautenticar o usuário antes de acessar áreas sensíveis.
     */
    public function show(): Response
    {
        return Inertia::render('Auth/ConfirmPassword');
    }

    /**
     * Confirma a senha do usuário.
     * Valida a senha fornecida e, se correta, marca a sessão como "senha confirmada".
     *
     * @param Request $request O objeto de requisição contendo a senha.
     * @return RedirectResponse Redireciona o usuário para a URL pretendida após a confirmação.
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Tenta validar as credenciais do usuário (e-mail e senha).
        // Se a validação falhar, uma exceção é lançada.
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email, // Usa o e-mail do usuário logado
            'password' => $request->password,   // Usa a senha fornecida na requisição
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'), // Mensagem de erro de validação de senha
            ]);
        }

        // Marca a sessão com um timestamp indicando que a senha foi confirmada recentemente.
        // Isso permite que o middleware 'password.confirm' saiba que o usuário não precisa confirmar novamente por um tempo.
        $request->session()->put('auth.password_confirmed_at', time());

        // Redireciona o usuário para a URL que ele pretendia acessar antes da confirmação de senha,
        // ou para o dashboard se não houver uma URL pretendida.
        // Ajustado para usar URL literal '/dashboard' conforme a abordagem "sem Ziggy".
        return redirect()->intended('/dashboard');
    }
}
