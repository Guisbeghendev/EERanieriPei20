<?php

namespace App\Models\Traits;

use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait HasRoles
 *
 * Este trait fornece métodos para gerenciar e verificar os papéis (roles) de um usuário.
 * Ele é uma implementação personalizada para substituir a necessidade de pacotes externos
 * de roles/permissões, conforme especificado no Módulo 1.
 */
trait HasRoles
{
    /**
     * Um usuário pode ter várias roles.
     * Define o relacionamento N:N entre User e Role.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        // Define explicitamente a tabela pivô ('role_user') e as chaves estrangeiras.
        // Embora o Laravel possa inferir isso, ser explícito é uma boa prática.
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /**
     * Verifica se o usuário possui uma ou mais roles específicas.
     *
     * @param string|array $role O nome da role (string) ou um array de nomes de roles.
     * @return bool Retorna true se o usuário possuir a(s) role(s) especificada(s), caso contrário, false.
     */
    public function hasRole(string|array $role): bool
    {
        // Garante que a relação 'roles' esteja carregada.
        // Isso evita múltiplas consultas ao banco de dados se 'hasRole' for chamado várias vezes.
        if (!$this->relationLoaded('roles')) {
            $this->load('roles');
        }

        // Se o parâmetro $role for um array, verifica se o usuário tem ALGUMA das roles listadas.
        if (is_array($role)) {
            foreach ($role as $r) {
                // Usa o método 'contains' da coleção para verificar a existência da role pelo nome.
                if ($this->roles->contains('name', $r)) {
                    return true; // Encontrou uma das roles, retorna verdadeiro.
                }
            }
            return false; // Não encontrou nenhuma das roles no array.
        }

        // Se o parâmetro $role for uma string, verifica se o usuário tem ESSA role específica.
        return $this->roles->contains('name', $role);
    }
}
