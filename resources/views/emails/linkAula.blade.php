
@component('mail::message')
# Aula {{$celula->aula->sigla}} : {{$celula->getDiaFormatado()}} {{$celula->horario}}

Aula do Professor: {{$celula->teacher->nome}}<br>
Segue o link do Zoom: <br>
<a href="{{$celula->aula_link}}"> {{$celula->aula_link}} </a>

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent
