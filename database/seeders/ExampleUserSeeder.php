<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Importe o modelo User
use Illuminate\Support\Facades\Hash; // Importe a Facade Hash

class ExampleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Cria um usuário de exemplo para testes gerais.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            [
                'email' => 'teste.ranieri@gmail.com',
            ],
            [
                'name' => 'Teste',
                'password' => Hash::make('Gsp@teste2025'), // Senha forte para o usuário de teste
                'email_verified_at' => now(), // Marca como verificado para facilitar testes
            ]
        );

        // O UserObserver se encarrega de criar o perfil em branco
        // e de associar este usuário ao grupo 'free' automaticamente.
        // Este seeder não atribui roles específicas, apenas cria o usuário base.
    }
}
