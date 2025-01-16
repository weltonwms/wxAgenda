
@component('mail::message')
# {{ config('app.name') }} te dá boas vindas!!

Seja Bem Vindo {{$student->nome}}
Segue abaixo uma senha provisória para você acessar o sistema de agendamento:
{{ url('/')}}

Senha provisória: {{$senhaTemporaria}}

Ao entrar, não esqueça de trocar a senha acessando o Perfil no menu superior direito.
{{-- Nota de Rodapé --}}
@component('mail::footer')
Email Automático, favor não responder.
@endcomponent


@endcomponent
