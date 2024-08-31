<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Celula;
use App\Models\Student;

/**
 * Notificação para o Teacher que um aluno desmarcou aula
 */
class NotificacaoAulaDesmarcada extends Mailable
{
    use Queueable, SerializesModels;

    public $celula;
    public $student;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Celula $celula, Student $student)
    {
        $this->celula = $celula;
        $this->student = $student;
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
                    ->subject('Aula Desmarcarda: ')
                    ->markdown('emails.aulaDesmarcada');
       
    }
}
