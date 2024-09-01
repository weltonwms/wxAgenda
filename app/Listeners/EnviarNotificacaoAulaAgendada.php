<?php

namespace App\Listeners;

use App\Events\AulaAgendada;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacaoAulaAgendada;
use Illuminate\Support\Facades\Log;
use App\Helpers\TelegramHelper;

class EnviarNotificacaoAulaAgendada
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\AulaAgendada  $event
     * @return void
     */
    public function handle(AulaAgendada $event)
    {
        $celula = $event->celula;
        $student = $event->student;
        //notificação somente de estiver faltando menos de 8 horas para começar aula.
        if($celula->HoursToStart() <=8){
            $this->notificarTeacherMarcacaoAula($celula, $student);
        }
    }

    private function notificarTeacherMarcacaoAula($celula, $student)
    {
         TelegramHelper::notificarAulaAgendada($celula, $student);        
         Mail::send(new NotificacaoAulaAgendada($celula, $student) );
         //Implementar outras notificações se Necessário: WhatsApp    
    }
}
