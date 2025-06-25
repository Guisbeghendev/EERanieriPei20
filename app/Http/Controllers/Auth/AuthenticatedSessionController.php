<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest; // Request customizado para validação de login
use Illuminate\Http\RedirectResponse;     // Tipo de retorno para redirecionamentos
use Illuminate\Http\Request;              // Objeto de requisição HTTP
use Illuminate\Support\Facades\Auth;      // Facade para gerenciar autenticação
use Illuminate\Support\Facades\Route;     // Facade para interagir com as rotas
use Inertia\Inertia;                      // Classe Inertia para renderizar páginas Vue
use Inertia\Response;                     // Tipo de retorno para respostas Inertia
use App\Providers\RouteServiceProvider;   // Provedor de serviços para constantes de rota

class AuthenticatedSessionController extends Controller
{
    /**
     * Exibe a view de login.
     * Retorna a página 'Auth/Login' para o frontend.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            // 'canResetPassword' é uma flag booleana enviada para o frontend.
            // O frontend usará essa flag para mostrar ou ocultar o link "Esqueceu sua senha?".
            // Route::has('password.request') verifica se a rota nomeada existe no backend.
            'canResetPassword' => Route::has('password.request'),
            // 'status' é uma mensagem de sessão (ex: "Sua senha foi redefinida!")
            // que pode ser exibida após um redirecionamento.
            'status' => session('status'),
        ]);
    }

    /**
     * Lida com uma requisição de autenticação (login).
     *
     * @param LoginRequest $request A requisição contendo as credenciais de login.
     * @return RedirectResponse Redireciona o usuário após o login bem-sucedido.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Tenta autenticar o usuário usando as credenciais fornecidas na requisição.
        // Se a autenticação falhar, uma exceção de validação é lançada.
        $request->authenticate();

        // Regenera a sessão para evitar ataques de fixação de sessão.
        $request->session()->regenerate();

        // Redireciona o usuário para a URL pretendida (onde ele queria ir antes do login)
        // ou para o '/dashboard' se não houver URL pretendida.
        // Usamos uma URL literal '/dashboard' para consistência com a abordagem 'sem Ziggy'.
        return redirect()->intended('/dashboard');
    }

    /**
     * Encerra uma sessão autenticada (logout).
     *
     * @param Request $request O objeto de requisição.
     * @return RedirectResponse Redireciona o usuário para a página inicial após o logout.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Realiza o logout do usuário da guarda 'web'.
        Auth::guard('web')->logout();

        // Invalida a sessão atual, removendo todos os dados da sessão.
        $request->session()->invalidate();

        // Regenera o token CSRF da sessão para segurança.
        $request->session()->regenerateToken();

        // Redireciona o usuário para a página inicial.
        return redirect('/');
    }

    /**
     * Exibe a view de confirmação de senha.
     * Esta view é usada para reautenticar o usuário antes de acessar áreas sensíveis.
     */
    public function confirmPassword(): Response
    {
        return Inertia::render('Auth/ConfirmPassword');
    }

    /**
     * Confirma a senha do usuário.
     * Verifica se a senha fornecida corresponde à senha atual do usuário.
     *
     * @param Request $request O objeto de requisição contendo a senha.
     * @return RedirectResponse Redireciona o usuário após a confirmação bem-sucedida.
     */
    public function storeConfirmedPassword(Request $request): RedirectResponse
    {
        // Valida a senha fornecida: deve ser obrigatória, string e corresponder à senha atual do usuário.
        $request->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        // Marca a sessão como "senha confirmada", permitindo o acesso a áreas sensíveis.
        $request->session()->passwordConfirmed();

        // Redireciona o usuário para a URL pretendida ou para a constante HOME definida.
        // Lembre-se de verificar se App\Providers\RouteServiceProvider::HOME está definido como '/dashboard'.
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
