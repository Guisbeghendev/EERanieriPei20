<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;                    // Importa o modelo User
use Illuminate\Auth\Events\Registered;   // Evento disparado após o registro de um usuário
use Illuminate\Http\RedirectResponse;    // Tipo de retorno para redirecionamentos
use Illuminate\Http\Request;             // Objeto de requisição HTTP
use Illuminate\Support\Facades\Auth;     // Facade para gerenciar autenticação
use Illuminate\Support\Facades\Hash;     // Facade para hash de senhas
use Illuminate\Validation\Rules;         // Regras de validação do Laravel
use Inertia\Inertia;                     // Classe Inertia para renderizar páginas Vue
use Inertia\Response;                    // Tipo de retorno para respostas Inertia

class RegisteredUserController extends Controller
{
    /**
     * Exibe a view de registro.
     * Retorna a página 'Auth/Register' para o frontend.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Lida com uma requisição de registro de entrada.
     * Cria um novo usuário, o autentica e o redireciona.
     *
     * @param Request $request A requisição contendo os dados do novo usuário.
     * @return RedirectResponse Redireciona o usuário após o registro bem-sucedido.
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Valida os dados da requisição.
        $request->validate([
            'name' => 'required|string|max:255',
            // O e-mail deve ser único na tabela de usuários.
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            // A senha deve ser obrigatória, confirmada e seguir as regras padrão do Laravel (mínimo de caracteres, etc.).
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Cria o novo usuário no banco de dados.
        // Lembre-se que o UserObserver (Módulo 6) será acionado aqui
        // para criar o perfil e associar o grupo 'free' automaticamente.
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash da senha para segurança.
        ]);

        // Dispara o evento 'Registered'. Este evento é crucial para observers (como UserObserver)
        // que precisam realizar ações adicionais após o registro.
        event(new Registered($user));

        // Autentica o usuário recém-registrado automaticamente.
        Auth::login($user);

        // Redireciona o usuário para a página de dashboard após o registro.
        // Usamos uma URL literal '/dashboard' para consistência com a abordagem 'sem Ziggy'.
        return redirect('/dashboard');
    }
}
