<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role; // Importe o modelo Role

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Popula a tabela 'roles' com os papéis definidos para a aplicação.
     */
    public function run(): void
    {
        // Lista de roles (funções dos usuários)
        $roles = [
            'admin',        // Gestão total do sistema
            'fotografo',    // Gestão de galerias e imagens
            'familia',      // Exemplo de role futura
            'aluno',        // Exemplo de role futura
            'professor',    // Exemplo de role futura
            'funcionario',  // Exemplo de role futura
            'gestao',       // Exemplo de role futura
            'DE',           // Exemplo de role futura
        ];

        // Cria cada role se ainda não existir.
        // O uso de firstOrCreate evita duplicações se o seeder for rodado várias vezes.
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }
    }
}
