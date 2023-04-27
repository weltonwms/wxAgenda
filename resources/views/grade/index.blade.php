@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>' Agendar Aula', 'icon'=>'fa-calendar', 'route'=>route('gradeEscola.index'),'subtitle'=>'Grade Horária 
 Completa da Escola/Agendamento'])

@endbreadcrumbs
@endsection

@section('content')
<div class="tile row tile-nomargin">
    <label class="text-primary">Créditos Atuais:</label>
    <button type="button" class="btn btn-outline-info btn-sm"> <span id="student_saldo_atual"></span></button>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <label class="text-primary">Módulo Corrente:</label>
    <button type="button" class="btn btn-outline-info btn-sm"> <span id="student_module_nome"></span></button>
    <input type="hidden" value="" id="student_id"> <!--Usado para validação de agenda-->
    <input type="hidden" value="" id="student_module_id"> <!--Usado para filtro aulas base-->
</div>

<input type="hidden" id="horarios_validos" value="{{$horariosList}}" class="form-control">
<div class="tile row">
    <div class="col-sm-12">
    {{ Form::bsSelect('teacher_id',$teachersList,old('teacher_id'),['label'=>"Escolha o Professor:"]) }}
    </div>


    <div class="col-sm-12">
        <div id='calendar' ></div>
    </div>

</div>

@include('grade.modal')

<div class="legenda_celula card col-md-5 pt-2">
    <h5>Legenda Cores</h5>
<ul class="card-body">
    <li><span class="badge legenda1 w60">Azul</span>: Marcação do Aluno</li>
    <li><span class="badge legenda2 w60">Verde</span>: Sala Vazia</li>
    <li><span class="badge legenda3 w60">Amarelo</span>: Sala Populada</li>
    <li><span class="badge legenda4 w60">Vermelho</span>: Sala Lotada (Indisponível)</li>

</ul>
</div>


@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('template/css/fullcalendar.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('template/js/plugins/fullcalendar.min.js') }}"></script>
<script src="{{ asset('js/grade.js') }}"></script>
@endpush