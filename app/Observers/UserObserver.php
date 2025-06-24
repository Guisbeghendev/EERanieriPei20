<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Group; // Importe o modelo Group
// A importação do modelo Role não é mais necessária aqui, pois não há atribuição de role neste observer.
// use App\Models\Role;

class UserObserver
{
    /**
     * Handle the User "created" event.
     * Este método é executado quando um novo usuário é criado.
     */
    public function created(User $user): void
    {
        // 1. Cria um perfil vazio associado ao novo usuário
        // Conforme definido no Módulo 1, cada usuário terá um perfil adicional.
        $user->profile()->create();

        // 2. Associa o grupo 'free' ao novo usuário
        // Conforme o Módulo 1, 'free' é o grupo padrão para usuários registrados e logados.
        // O método firstOrCreate garante que o grupo 'free' seja criado se não existir, e então o associa ao usuário.
        $group = Group::firstOrCreate(['name' => 'free']);
        $user->groups()->syncWithoutDetaching([$group->id]);

        // A atribuição de Roles como 'administrador' ou 'fotografo' não é feita automaticamente aqui.
        // Isso será gerenciado via seeds ou através da interface administrativa por um Role/Group Manager.
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user): void
    {
        // Lógica para quando um usuário é atualizado, se necessário
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user): void
    {
        // Lógica para quando um usuário é deletado, se necessário
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user): void
    {
        // Lógica para quando um usuário é restaurado, se necessário
    }

    /**
     * Handle the User "forceDeleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user): void
    {
        // Lógica para quando um usuário é forçadamente deletado, se necessário
    }
}
