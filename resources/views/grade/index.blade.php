@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>' Grade da Escola', 'icon'=>'fa-map', 'route'=>route('gradeEscola.index'),'subtitle'=>'Grade Horária 
 Completa da Escola'])

@endbreadcrumbs
@endsection

@section('content')

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