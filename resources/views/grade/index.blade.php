@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>' Grade da Escola', 'icon'=>'fa-map', 'route'=>route('gradeEscola.index'),'subtitle'=>'Grade Horária 
 Completa da Escola'])

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


@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('template/css/fullcalendar.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('template/js/plugins/fullcalendar.min.js') }}"></script>
<script src="{{ asset('js/grade.js') }}"></script>
@endpush