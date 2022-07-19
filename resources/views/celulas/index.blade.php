@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>' Células', 'icon'=>'fa-calendar', 'route'=>route('celulas.index'),'subtitle'=>'Gerenciamento de 
 Células'])

@endbreadcrumbs
@endsection

@section('content')
<input type="hidden" id="horarios_validos" value="{{$horariosList}}" class="form-control">
<div class="tile row">

    <div class="col-sm-3">
        {!! Form::open(['route'=>'celulasBath.store','class'=>'','id'=>'adminForm'])!!}

        {{ Form::bsSelect('teacher_id',$teachersList,old('teacher_id'),['label'=>"Escolha o Professor:"]) }}


        <div class="bg-light border p-2 mb-3">
            <div>
                <h6 class="text-center">Construtor de Disponibilidade</h6>
            </div>

            <div class="form-group">
                <label for="periodo_inicio" class="mb-1">Início:</label>
                <input type="date" name="periodo_inicio" id="periodo_inicio" class="form-control form-control-sm">

            </div>

            <div class="form-group">
                <label for="periodo_fim" class="mb-1">Fim:</label>
                <input type="date" name="periodo_fim" id="periodo_fim" class="form-control form-control-sm">

            </div>

            <div class="form-group">
                <button class="btn btn-outline-primary">Executar</button>
            </div>
        </div>

        {!! Form::close() !!}

    </div>


    <div class="col-sm-9">
        <div id='calendar' ></div>
    </div>

</div>

@include('celulas.modal')


@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('template/css/fullcalendar.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('template/js/plugins/fullcalendar.min.js') }}"></script>
<script src="{{ asset('js/celulas.js') }}"></script>
@endpush