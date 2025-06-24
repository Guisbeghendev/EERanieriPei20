<?php

namespace App\Providers;

use App\Models\User;         // Importe o modelo User
use App\Observers\UserObserver; // Importe o Observer

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event; // Importação comum, embora Event não seja usado diretamente no $observers.

class EventServiceProvider extends ServiceProvider
{
    /**
     * Os observadores de modelo para sua aplicação.
     * Mapeia um modelo a um ou mais observadores.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $observers = [
        User::class => [UserObserver::class], // <-- REGISTRO DO USER OBSERVER AQUI
    ];

    /**
     * Os mapeamentos de eventos para listeners para a aplicação.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Registra quaisquer eventos para sua aplicação.
     */
    public function boot(): void
    {
        // O método 'boot' pode ser usado para outras inicializações,
        // mas o registro de observadores via '$observers' é preferível.
        // User::observe(UserObserver::class); // Alternativa, mas não usada se '$observers' está configurado.
    }

    /**
     * Determina se eventos e listeners devem ser automaticamente descobertos.
     * Definir como 'false' significa que você registrará manualmente seus observadores e listeners.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
