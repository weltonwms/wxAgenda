<?php

namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Celula;
use App\Models\Student;
use Illuminate\Support\Facades\Log;

/**
 * Notificação para o Teacher que um aluno agendou aula com pouco tempo
 */
class NotificacaoAulaAgendada extends Mailable
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
                    ->subject('Nova Aula Agendada: ')
                    ->markdown('emails.aulaAgendada');
       
    }

    public function send($mailer)
    {
        Log::channel('daily_email_logs')->info('Tentando enviar e-mail', [
        'para'    => $this->celula->teacher->email,
        'assunto' => 'Nova Aula Agendada',
        'aluno'   => $this->student->name,
        'celula'  => $this->celula->id,
        ]);
        
        
        try {
            parent::send($mailer);
            Log::channel('daily_email_logs')->info('E-mail enviado com sucesso', [
            'para'   => $this->celula->teacher->email,
            'aluno'  => $this->student->name,
            'celula' => $this->celula->id,
            ]);
        } catch (\Exception $e) {
            // Trate a exceção e registre no log
            //Log::channel('daily_email_logs')->error('Erro no envio do e-mail: ' . $e->getMessage());
            Log::channel('daily_email_logs')->error('Erro no envio do e-mail', [
            'para'    => $this->celula->teacher->email,
            'aluno'   => $this->student->name,
            'celula'  => $this->celula->id,
            'erro'    => $e->getMessage(),
            'arquivo' => $e->getFile(),
            'linha'   => $e->getLine(),
        ]);
        }
    }
}
