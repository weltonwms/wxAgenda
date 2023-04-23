<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Message
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        /*
        //Deixar o admin sem restrição
        if (auth()->user()->isAdm) {
            return $next($request);
        }
        */
        $authId=auth()->user()->id;
        $message=$request->route('message');
        
        if($message && $message->sender_id !== $authId && $message->recipient_id !== $authId) {
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => 'Você não tem permissão para visualizar esta mensagem.!']);
            return redirect()->route('messages.index');
            
        }
        return $next($request);
    }
}
