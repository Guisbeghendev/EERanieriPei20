<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;  // Importe o modelo User
use App\Models\Role;  // Importe o modelo Role
use App\Models\Group; // Importe o modelo Group
use Illuminate\Support\Facades\Hash; // Importe a Facade Hash

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Cria ou atualiza o usuário administrador e associa as roles/grupos corretos.
     */
    public function run(): void
    {
        // Garante que a role "admin" exista.
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Garante que o grupo "admin" exista.
        $adminGroup = Group::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Grupo de administradores do sistema']
        );

        // Cria ou busca o usuário admin.
        // updateOrCreate é usado para idempotência: cria se não existe, atualiza se existe.
        $admin = User::updateOrCreate(
            ['email' => 'admin.ranieri@gmail.com'], // Condição para buscar o usuário
            [
                'name' => 'Administrador',
                'password' => Hash::make('Gsp@ranieri2025'), // Senha segura para o admin
                'email_verified_at' => now(), // Define email_verified_at para simular um usuário verificado
            ]
        );

        // Associa a role 'admin' ao usuário.
        // O `syncWithoutDetaching` garante que a role seja adicionada se não estiver presente,
        // sem remover outras roles caso o usuário já as tenha (embora para um admin,
        // esperemos que 'admin' seja a única role principal).
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        // Associa o grupo 'admin' ao usuário.
        $admin->groups()->syncWithoutDetaching([$adminGroup->id]);
    }
}
