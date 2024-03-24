
@component('mail::message')
# {{ config('app.name') }} informa nova mensagem

## {{$message->subject}}

### De: {{$message->sender->nome}}                        {{date('d/m/Y H:i')}}

{{$message->body}}

{{-- Nota de Rodapé --}}
@component('mail::footer')
Email Automático, favor não responder.
@endcomponent


@endcomponent
