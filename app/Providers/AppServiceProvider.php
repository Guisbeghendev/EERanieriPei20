<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite; // Importa a fachada Vite para configuração de pré-carregamento
use Illuminate\Support\ServiceProvider; // Classe base para Service Providers

// As linhas abaixo foram REMOVIDAS, pois o UserObserver é registrado no EventServiceProvider.
// use App\Models\User;
// use App\Observers\UserObserver;

/**
 * Service Provider principal da aplicação.
 * Usado para registrar quaisquer serviços da aplicação ou para bootar funcionalidades.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra quaisquer serviços da aplicação.
     * Este método é chamado ANTES de todos os outros service providers.
     * Ele não deve interagir com outros services.
     */
    public function register(): void
    {
        // Este método está vazio, o que é comum se não houver registros de serviço complexos aqui.
    }

    /**
     * Bootstrap (inicializa) quaisquer serviços da aplicação.
     * Este método é chamado DEPOIS que todos os outros service providers foram registrados.
     * É o local ideal para registrar eventos, observadores, definir Gates/Policies (se não forem no AuthServiceProvider), etc.
     */
    public function boot(): void
    {
        // Configura o pré-carregamento de assets do Vite para otimizar a performance.
        // O Laravel Vite Plugin usa isso para adicionar automaticamente tags <link rel="preload">
        // ou <link rel="prefetch"> para assets, melhorando a velocidade de carregamento.
        Vite::prefetch(concurrency: 3); // Define a concorrência para 3 pré-carregamentos simultâneos.

        // REMOVIDO: Registro do UserObserver
        // Esta linha (User::observe(UserObserver::class);) foi removida daqui
        // porque o UserObserver já está sendo registrado corretamente no
        // App\Providers\EventServiceProvider.php.
        // Manter a linha aqui resultaria em um registro duplicado, o que pode causar
        // comportamentos inesperados ou erros.
    }
}


