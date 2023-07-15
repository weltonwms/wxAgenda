<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Student;
use App\Helpers\TelegramHelper;

class TelegramController extends Controller
{
    public function subscribe(Request $request){
    	$dados=$request->all();
    	$dados['data_acao']=date('Y-m-d H:i');
        Storage::append('telegram.log', json_encode($dados));
        $text= $dados['message']['text'];
        $chat_id= $dados['message']['chat']['id'];
       
        if($text=='/start'){
            $mensagem="Para se inscrever digite seu email, conforme no sistema da IdiomClub";
            TelegramHelper::sendMessage($chat_id,$mensagem);
            return response()->json(['ok'=>true]);
            // Exit com código 200
        }
        $student= $this->saveChatIdAluno($text, $chat_id);
        if(!$student){
            $mensagem="Email não encontrado no sistema da IdiomClub";
            TelegramHelper::sendMessage($chat_id,$mensagem);
            return response()->json(['ok'=>true]);
            // Exit com código 200
        }

        $mensagem="Parabéns {$student->nome}! Sua inscrição foi realizada com Sucesso!";
        $mensagem.=" Agora você poderá receber por esse canal os links das Aulas.";
        TelegramHelper::sendMessage($chat_id,$mensagem);
        return response()->json(['ok'=>true]);
        // Exit com código 200
    }





    private function saveChatIdAluno($email,$chat_id)
    {
        $student= Student::where('email',$email)->first();       
        if($student){
            //gravar no banco de dados o student com seu chat id
            $student->chat_id=$chat_id;
            $retorno=$student->save();
            if(!$retorno){
                return false;
            }
        }
        return $student;
    }

    public function teste()
    {
        $chat_id ="teste";
        $message ='Teste a partir do laravel';
        //dd(TelegramHelper::sendMessage($chat_id, $message));
        //$celula= \App\Models\Celula::find(2024);
        //return response()->json($celula->onSaveAulaLink());
    }
}
