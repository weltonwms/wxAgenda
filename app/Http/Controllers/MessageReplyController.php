<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageReplyRequest;
use App\Models\MessageReply;
use Illuminate\Http\Request;

class MessageReplyController extends Controller
{
   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     */
    public function store(MessageReplyRequest $request)
    {
       $reply= new MessageReply();
       $reply->message_id= $request->message_id;
       $reply->user_id= auth()->user()->id;
       $reply->reply_text= $request->reply_text;
       $reply->save();
       return back();
    }

   
}
