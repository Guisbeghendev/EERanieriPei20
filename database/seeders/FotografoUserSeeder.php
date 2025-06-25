<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;  // Importe o modelo User
use App\Models\Role;  // Importe o modelo Role
use App\Models\Group; // Importe o modelo Group
use Illuminate\Support\Facades\Hash; // Importe a Facade Hash

class FotografoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Cria ou atualiza o usuário fotógrafo e associa as roles/grupos corretos.
     */
    public function run(): void
    {
        // Garante que a role "fotografo" exista.
        $fotografoRole = Role::firstOrCreate(['name' => 'fotografo']);

        // Garante que o grupo "fotografo" exista.
        $fotografoGroup = Group::firstOrCreate(
            ['name' => 'fotografo'],
            ['description' => 'Grupo de usuários fotógrafos']
        );

        // Cria ou busca o usuário fotógrafo.
        $fotografo = User::updateOrCreate(
            ['email' => 'fotografo.ranieri@gmail.com'], // Condição para buscar o usuário
            [
                'name' => 'Fotógrafo',
                'password' => Hash::make('Gsp@ranieri2025'), // Senha segura para o fotógrafo
                'email_verified_at' => now(),
            ]
        );

        // Associa a role 'fotografo' ao usuário.
        $fotografo->roles()->syncWithoutDetaching([$fotografoRole->id]);

        // Associa o grupo 'fotografo' ao usuário.
        $fotografo->groups()->syncWithoutDetaching([$fotografoGroup->id]);
    }
}
