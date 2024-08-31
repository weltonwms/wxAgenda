@component('mail::message')
# Aula Desmarcada: {{$celula->aula->sigla}} : {{$celula->getDiaFormatado()}} {{$celula->horario}}

Desmarcação Realizada por: <br>
Aluno: {{$student->nome}}<br>
Aula do Professor: {{$celula->teacher->nome}}<br>


Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent
