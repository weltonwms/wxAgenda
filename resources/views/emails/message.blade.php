
@component('mail::message')
# {{ config('app.name') }} informa nova mensagem

## {{$message->subject}}

<?php
$sender = $message->sender? $message->sender->nome: Auth::user()->username 
?>
### De: {{$sender}}                              {{date('d/m/Y H:i')}}

{{$message->body}}

{{-- Nota de Rodapé --}}
@component('mail::footer')
Email Automático, favor não responder.
@endcomponent


@endcomponent
