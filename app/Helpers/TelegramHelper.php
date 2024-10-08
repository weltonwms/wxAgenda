<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Storage;
use App\Models\Message;


class TelegramHelper
{

    public static function sendMessage($chat_id, $message)
    {
        if(!$chat_id){
            return false; //não tem como mandar sem chat_id;
        }
        $base_url = env('TELEGRAM_BASE_URL', '');
        $token = env('TELEGRAM_TOKEN', '');
        $url = $base_url . "/bot" . $token . "/sendMessage";
       
        $ch = curl_init($url);
        $payload = json_encode([
            "chat_id" => $chat_id,
            "text" => $message
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        // Check if any error occurred
        if (curl_errno($ch)) {
            \Log::error( "Falha SendMessage TeleGram: Chat_id:$chat_id, message:$message ".curl_error($ch));
            return false;
        }        
        curl_close($ch);       
        $resultDecode= json_decode($result);
        if(isset($resultDecode->ok) && $resultDecode->ok){
           return true;
        }
        //Retorno de erro da API
        \Log::error( "Falha SendMessage TeleGram: Chat_id:$chat_id, message:$message ".$result);
        return false;
    }

    public static function notificarAlunosDaCelula($celula)
    {
        $horario = $celula->horario;
        $dia = $celula->getDiaFormatado();
        $teacher = $celula->teacher->nome;
        $aula= $celula->aula->sigla;
        $message="Aula: $aula; Dia: $dia; Horário: $horario; Professor: $teacher; ";
        $message.="Link Zoom: {$celula->aula_link}";

        foreach($celula->students as $student){
            if($student->chat_id && $celula->aula_link){
                TelegramHelper::sendMessage($student->chat_id,$message);                
            }
            else{
                $dados=[
                    "data_acao"=>date('Y-m-d H:i'),
                    "error"=>"Aluno ({$student->nome}) sem chat_id ou célula ({$celula->id})",
                    "message"=>$message
                ];
                Storage::append('telegram_notificacao.log', json_encode($dados));
            }
        }
    }

    public static function notificarMessage(Message $message)
    {
        $date = date('d/m/Y H:i');
        $texto = "Nova mensagem no Sistema: \n De: {$message->sender->nome}, $date \n";
        $texto .= " {$message->subject} \n";
        $texto .= " {$message->body}";
        TelegramHelper::sendMessage($message->recipient->chat_id, $texto); 

    }

    public static function notificarAberturaAulaReview($celula, $student=null)
    {
        $horario = $celula->horario;
        $dia = $celula->getDiaFormatado();
        $teacher = $celula->teacher;
        $studentNome = $student?$student->nome:'';
        $infoStudentNome = $studentNome?" Aluno: $studentNome; ":'';
        $aula= $celula->aula->sigla;

        $message= "Nova Solicitação de Aula Review; $infoStudentNome \n";
        $message.="Aula: $aula; Dia: $dia; Horário: $horario; Professor: {$teacher->nome}; \n";
        $message.="Tipo de Review Solicitada: {$celula->reviewInfo->tipo_review_name} \n";
        $message.="Descrição da Review Solicitada:: {$celula->reviewInfo->descricao_review} \n";

        TelegramHelper::sendMessage($teacher->chat_id, $message); 
    }

    public static function notificarAulaAgendada($celula, $student)
    {
        $horario = $celula->horario;
        $dia = $celula->getDiaFormatado();
        $teacher = $celula->teacher;        
        $aula= $celula->aula->sigla;

        $message= "Aula Agendada por Aluno: {$student->nome} \n";
        $message.="Aula: $aula; Dia: $dia; Horário: $horario; Professor: {$teacher->nome}; \n";
        
        TelegramHelper::sendMessage($teacher->chat_id, $message); 

    }

    public static function notificarAulaDesmarcada($celula, $student)
    {
        $horario = $celula->horario;
        $dia = $celula->getDiaFormatado();
        $teacher = $celula->teacher;        
        $InfoAula= $celula->aula?"Aula: {$celula->aula->sigla}; ":'';

        $message= "Aula Desmarcada por Aluno: {$student->nome} \n";
        $message.="{$InfoAula}Dia: $dia; Horário: $horario; Professor: {$teacher->nome}; \n";
        
        TelegramHelper::sendMessage($teacher->chat_id, $message); 

    }

    
}