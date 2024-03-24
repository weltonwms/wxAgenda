<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificacaoMessage extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var \App\Models\Message
     */
    public $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->message->recipient->email;
        $assunto = $this->message->subject;

        return $this->to($email)
                    ->subject('Nova Mensagem: '.$assunto)
                    ->markdown('emails.message');
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
