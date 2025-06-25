<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group; // Importe o modelo Group

class GroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Popula a tabela 'groups' com os grupos de acesso.
     */
    public function run(): void
    {
        // Lista de nomes de grupos.
        // O grupo 'free' é o grupo padrão para novos usuários registrados (Módulo 6).
        $groupNames = [
            'free',         // Grupo padrão para usuários registrados e logados
            'admin',        // Grupo para usuários administradores
            'fotografo',    // Grupo para usuários fotógrafos
        ];

        // Cria cada grupo se ainda não existir.
        foreach ($groupNames as $groupName) {
            Group::firstOrCreate(
                ['name' => $groupName],
                [
                    'description' => "Grupo de acesso para '{$groupName}'.",
                ]
            );
        }

        // Lembrete: O UserObserver é responsável por associar o grupo 'free'
        // a todo novo usuário cadastrado automaticamente.
    }
}
