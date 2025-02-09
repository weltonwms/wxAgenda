<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Mail\NotificacaoMessage;
use Illuminate\Support\Facades\Mail;
use App\Helpers\TelegramHelper;
use App\Models\Student;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'subject',
        'body',
        'is_read'
    ];


    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function replies()
    {
        return $this->hasMany(MessageReply::class);
    }

    public function markAsRead()
    {
        $user = auth()->user();
        if (!$this->is_read && $user->id == $this->recipient_id) {
            $this->is_read = 1;
            $this->save();
            $user->onMessageRead(); //aviso ao User de Nova mensagem marcada como lida
        }
    }

    public static function markAsDeleted($ids, $sent)
    {
        if (!$ids) {
            return false;
        }

        $user = auth()->user();
        $retorno = 0;

        if ($sent == 1) {
            $retorno = Message::whereIn('id', $ids)
                ->where('sender_id', $user->id)
                ->update(['sender_delete' => 1]);
        }

        if ($sent == 0) {
            $retorno = Message::whereIn('id', $ids)
                ->where('recipient_id', $user->id)
                ->update(['recipient_delete' => 1]);
        }
        $user->onMessageRead(); //atulizar mensagens não lidas do usuário
        Message::clearDeleted(); //Limpeza física de registros apagados dos 2 lados.
        return $retorno;
    }

    private static function clearDeleted()
    {
        Message::where('sender_delete', 1)
            ->where('recipient_delete', 1)
            ->delete();
    }


    public function getShortCreatedAt()
    {
        // Verifica se a data é do mesmo dia
        if ($this->created_at->isToday()) {
            return $this->created_at->format('H:i');
        }
        // Caso contrário, retorna a data sem a hora
        return $this->created_at->format('d/m/Y');
    }


    public function getSubjectBody()
    {
        
        $isNotRead= $this->is_read?"":"is_not_read";
        $openTag="<span class='title $isNotRead'>";
        $closeTag="</span>";

        /*
        //Não precisa cortar string aqui. Por css corta
        //$size=80;        
        //$result = $this->subject . " - |" . $this->body;
        if (strlen($result) > $size) {            
            $strShort = $openTag.substr($result, 0, $size);            
            $strShort=str_replace("|", "</span><span class='body'>", $strShort);
            $strShort.= "...".$closeTag;           
            //return $strShort;
        }
        */

        $strFull= $openTag.$this->subject."</span> - <span class='body'>".$this->body.$closeTag;
        return $strFull;       
    }

    /**
     * Gatilhos realizados ao salvar uma mensagem.
     */
    public function onSaveMessage()
    {
        $condicaoDisparoMail =  !($this->recipient->isStudent && $this->sender->isStudent);
        $condicaoDisparoMail = $condicaoDisparoMail && $this->recipient->email;
        if($condicaoDisparoMail):
            try{
                Mail::send(new NotificacaoMessage($this));
            }
            catch(\Exception $e){
                //não fazer nada se der erro. Já foi salvo no log
            }           
        endif;

        $condicaoTelegram = $this->recipient->chat_id;
        if($condicaoTelegram):
            TelegramHelper::notificarMessage($this); 
        endif;

    }

    /**
     * Envia mensagem para vários alunos de uma vez
     * @param array $ids id dos alunos
     * @param string $subject assunto
     * @param string $body  texto
     * @return boolean
     */
    public static function sendBath($ids,$subject, $body)
    {
        if (!$ids) {
            return false;
        }
        try{
            $listaRecipients =  Student::getEmailsByIds($ids);;
            $message = new Message();
            $message->subject = $subject;
            $message->body = $body;
            Mail::send(new NotificacaoMessage($message, $listaRecipients));
            return true;
        }catch(\Exception $e){
            return false;
        }
    }

}