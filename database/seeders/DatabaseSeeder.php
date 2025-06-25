<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Este é o seeder principal que chama os outros seeders na ordem correta de dependência.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,     // Primeiro: cria todas as roles
            GroupsSeeder::class,    // Segundo: cria todos os grupos
            ExampleUserSeeder::class, // Terceiro: cria o usuário de exemplo
            AdminUserSeeder::class, // Quarto: cria o usuário admin e associa role/grupo
            FotografoUserSeeder::class, // Quinto: cria o usuário fotógrafo e associa role/grupo
            // Adicione outros seeders de dados aqui, se houver dependências, siga a ordem.
        ]);
    }
}
