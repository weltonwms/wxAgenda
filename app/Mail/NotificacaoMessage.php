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
     * Lista de destinatários.
     *
     * @var array
     */
    public $recipients;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Message $message, array $recipients = [])
    {
        $this->message = $message;
        $this->recipients = !empty($recipients) ? $recipients : ($message->recipient->email ?? []);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {        
        $assunto = $this->message->subject;
        $recipients = is_array($this->recipients) ? $this->recipients : [$this->recipients];
       
        // Se não houver destinatários, loga um erro e lança erro
        if (empty($recipients)) {
            Log::channel('daily_email_logs')->error('Tentativa de envio de e-mail sem destinatário.');
            throw new \Exception('Não há destinatários para o envio do e-mail.');
        }
        return $this->bcc($recipients)
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
            throw $e;
        }
    }
}
