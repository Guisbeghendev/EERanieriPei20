<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra quaisquer serviços de aplicação.
     *
     * @return void
     */
    public function register(): void
    {
        // Este método é usado para registrar bindings no container de serviço.
        // Por exemplo: $this->app->bind(SomeInterface::class, SomeImplementation::class);
    }

    /**
     * Inicializa quaisquer serviços de aplicação.
     *
     * @return void
     */
    public function boot(): void
    {
        // Otimização para o Vite: pré-busca de assets para carregamento mais rápido.
        // Concurrency: 3 significa que até 3 assets serão pré-buscados simultaneamente.
        Vite::prefetch(concurrency: 3);

        // REMOVIDO: Registro do observer (User::observe(UserObserver::class);)
        // Esta linha foi removida daqui porque o UserObserver já foi registrado
        // corretamente no App\Providers\EventServiceProvider.php (Módulo 6).
        // Registrá-lo aqui novamente causaria duplicação e potencial comportamento inesperado.
    }
}
