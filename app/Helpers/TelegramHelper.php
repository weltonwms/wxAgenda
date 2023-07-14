<?php
namespace App\Helpers;


class TelegramHelper
{

    public static function sendMessage($chat_id, $message)
    {
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
}