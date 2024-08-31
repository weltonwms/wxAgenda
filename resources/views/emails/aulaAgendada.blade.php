@component('mail::message')
# Aula Agendada: {{$celula->aula->sigla}} : {{$celula->getDiaFormatado()}} {{$celula->horario}}

Agendamento Realizado por: <br>
Aluno: {{$student->nome}}<br>
Aula do Professor: {{$celula->teacher->nome}}<br>


Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent
