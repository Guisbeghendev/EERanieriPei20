<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;    // Tipo de retorno para redirecionamentos
use Illuminate\Http\Request;             // Objeto de requisição HTTP
use Illuminate\Support\Facades\Hash;     // Facade para hash de senhas
use Illuminate\Validation\Rules\Password; // Regras de validação de senha do Laravel

class PasswordController extends Controller
{
    /**
     * Atualiza a senha do usuário.
     * Este método é tipicamente acessado via a página de perfil do usuário.
     *
     * @param Request $request O objeto de requisição contendo a senha atual e a nova senha.
     * @return RedirectResponse Redireciona de volta para a página anterior.
     */
    public function update(Request $request): RedirectResponse
    {
        // Valida as senhas fornecidas:
        // 'current_password': deve ser obrigatória e corresponder à senha atual do usuário.
        // 'password': deve ser obrigatória, seguir as regras padrão de senha do Laravel, e ser confirmada.
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // Atualiza a senha do usuário no banco de dados, fazendo o hash da nova senha.
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Redireciona o usuário de volta para a página anterior (geralmente a página de perfil),
        // sem usar rotas nomeadas.
        return back();
    }
}
