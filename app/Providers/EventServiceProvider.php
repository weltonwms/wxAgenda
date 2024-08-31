<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\AulaAgendada;
use App\Events\AulaDesmarcada;
use App\Listeners\EnviarNotificacaoAulaAgendada;
use App\Listeners\EnviarNotificacaoAulaDesmarcada;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        AulaAgendada::class => [
            EnviarNotificacaoAulaAgendada::class,
        ],
        AulaDesmarcada::class => [
            EnviarNotificacaoAulaDesmarcada::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
