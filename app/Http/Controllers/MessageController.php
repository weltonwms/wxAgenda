<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\User;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('message')->only('show');
    }

    /**
     * Display a listing of the resource.
     *
     * 
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $perPage=10;
        //\DB::enableQueryLog();
        if($request->sent){
            
            $messages = $user->sentMessages()
            ->where('sender_delete',0)
            ->with('recipient')->latest()->paginate($perPage);            
        }
        else{
            
            $messages = $user->receivedMessages()
            ->where('recipient_delete',0)
            ->with('sender')->latest()->paginate($perPage);
        }
        
        $subView = "messages.inbox";
        return view('messages.index', compact('messages', 'subView'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * 
     */
    public function create()
    {
        $usersList = User::getListToMessages();        
        $subView = "messages.create";
        return view('messages.index', compact('subView', 'usersList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     */
    public function store(MessageRequest $request)
    {
       // dd(request()->query());
        $message = new Message();
        $message->sender_id = auth()->user()->id;
        $message->recipient_id = $request->recipient_id;
        $message->subject = $request->subject;
        $message->body = $request->body;
        $message->is_read = 0;
        $message->save();
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => 'Mensagem Enviada com Sucesso!']);
        //$url = url('messages')->with(request()->query());
        return redirect()->route('messages.index',request()->query());
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * 
     */
    public function show(Message $message)
    {
        /*
        if($message->user_id !== $user->id) {
            return redirect()->route('messages.index')->with('error', 'Você não tem permissão para visualizar esta mensagem.');
        }
        */
        $message->markAsRead();        
        $message->load('replies.user');        
        $subView = "messages.read";
        return view('messages.index', compact('message', 'subView'));
    }

   

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * 
     */
    public function destroy(Message $message)
    {
        //
    }

    public function destroyBath()
    {
        $retorno = Message::markAsDeleted(request('ids'),request('sent') );
        return response()->json(['registros_afetados'=>$retorno]);
    }
}