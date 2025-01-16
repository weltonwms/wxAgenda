<?php

namespace App\Mail;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewStudent extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var \App\Models\Student
     */
    public $student;
    public $senhaTemporaria;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Student $student, $senhaTemporaria)
    {
        $this->student = $student;
        $this->senhaTemporaria = $senhaTemporaria;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->student->email;
        $assunto = "Acesso ao Sistema de Agendamento da IdiomClub";

        return $this->to($email)
                    ->subject($assunto)
                    ->markdown('emails.newStudent');
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
