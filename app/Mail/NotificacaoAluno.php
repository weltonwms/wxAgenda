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
        // 1. Coleta dados básicos para o contexto do log
        $emails = $this->celula->students->pluck('email')->toArray();
        $celulaId = $this->celula->id ?? 'N/A';

        Log::channel('daily_email_logs')->info('Iniciando conexao SMTP para envio', [
            'celula_id' => $celulaId,
            'destinatarios' => $emails,
            'hora_inicio' => now()->format('H:i:s.v')
        ]);

        // 2. Marca o tempo inicial de execução em microssegundos
        $tempoInicio = microtime(true);

        try {
            // Executa o envio real herdado da classe base Mailable
            parent::send($mailer);

            // 3. Calcula o tempo total gasto na conexão e transmissão
            $tempoTotal = round(microtime(true) - $tempoInicio, 2);

            Log::channel('daily_email_logs')->info('Envio concluido com sucesso pelo SMTP', [
                'celula_id' => $celulaId,
                'tempo_gasto_segundos' => $tempoTotal,
                'hora_fim' => now()->format('H:i:s.v')
            ]);

        } catch (\Exception $e) {
            // Calcula o tempo até a falha ocorrer
            $tempoFalha = round(microtime(true) - $tempoInicio, 2);

            // Trata a exceção e registra no mesmo canal de log
            Log::channel('daily_email_logs')->error('Erro no envio do e-mail', [
                'celula_id' => $celulaId,
                'tempo_ate_falha_segundos' => $tempoFalha,
                'erro_mensagem' => $e->getMessage(),
                'erro_linha' => $e->getLine()
            ]);
        }
    }
}
