@component('mail::message')
<?php 
    $siglaAula = $celula->aula?$celula->aula->sigla:'';
?>
# Aula Desmarcada: {{$siglaAula}} : {{$celula->getDiaFormatado()}} {{$celula->horario}}

Desmarcação Realizada por: <br>
Aluno: {{$student->nome}}<br>
Aula do Professor: {{$celula->teacher->nome}}<br>


Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent
