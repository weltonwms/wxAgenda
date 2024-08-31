<?php

namespace App\Mail;

use App\Models\Celula;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AberturaAulaReview extends Mailable
{
    use Queueable, SerializesModels;
     /**
     * The order instance.
     *
     * @var \App\Models\Celula
     */
    public $celula;

    public $student;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Celula $celula, $student=null)
    {
        $this->celula = $celula;
        if($student){
            $this->student = $student;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->celula->teacher->email;
        return $this->to($email)
        ->subject('Nova Célula de Aula de Review')
        ->markdown('emails.aberturaReview');
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
