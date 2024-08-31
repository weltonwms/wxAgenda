<?php

namespace App\Listeners;

use App\Events\AulaDesmarcada;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacaoAulaDesmarcada;
use App\Helpers\TelegramHelper;

class EnviarNotificacaoAulaDesmarcada
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
     * @param  \App\Events\AulaDesmarcada  $event
     * @return void
     */
    public function handle(AulaDesmarcada $event)
    {
        $celula = $event->celula;
        $student = $event->student;
        if($celula->HoursToStart() <=8){
            $this->notificarTeacherDesmarcacaoAula($celula, $student);
        }       
    }

    private function notificarTeacherDesmarcacaoAula($celula, $student)
    {
        TelegramHelper::notificarAulaDesmarcada($celula, $student);        
        Mail::send(new NotificacaoAulaDesmarcada($celula, $student) );
        //Implementar outras notificações se Necessário: WhatsApp
        Log::warning('desmarcacao realizada.', ['objeto' => json_encode($celula)]);


    }

    
}
