<?php

namespace App\Mail;

use App\Models\Celula;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificacaoAluno extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var \App\Models\Celula
     */
    public $celula;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Celula $celula)
    {
        $this->celula = $celula;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $emails= $this->celula->students->pluck('email');
        return $this->to($emails)
                    ->subject('Notificação de Aulas')
                    ->markdown('emails.linkAula');
    }

    public function send($mailer)
    {
        try {
            parent::send($mailer);
        } catch (\Exception $e) {
            // Trate a exceção e registre no log
            Log::channel('daily_email_logs')->error('Erro no envio do e-mail: ' . $e->getMessage());
        }
    }
}
