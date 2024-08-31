@component('mail::message')
# Aula {{$celula->aula->sigla}} : {{$celula->getDiaFormatado()}} {{$celula->horario}}

Aluno: {{$celula->student->nome}}<br>
Aula do Professor: {{$celula->teacher->nome}}<br>
Tipo de Review Solicitada: {{$celula->review_info->tipo_review}}<br>
Descrição da Review Solicitada: {{$celula->review_info->descricao_review}}<br>


Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent
