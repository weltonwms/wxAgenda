@component('mail::message')
# Aula {{$celula->aula->sigla}} : {{$celula->getDiaFormatado()}} {{$celula->horario}}

@if($student)
Aluno: {{$student->nome}}<br>
@endif
Aula do Professor: {{$celula->teacher->nome}}<br>
Tipo de Review Solicitada: {{$celula->reviewInfo->tipo_review_name}}<br>
Descrição da Review Solicitada: {{$celula->reviewInfo->descricao_review}}<br>


Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent
