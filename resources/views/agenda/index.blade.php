@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>' Marcar Aula', 'icon'=>'fa-calendar', 'route'=>route('agenda.index'),'subtitle'=>'Marcação de
Aulas'])

@endbreadcrumbs
@endsection


@section('content')
<input type="text"  id="aula_id" value="" class="form-control">
<div class="tile row">

    <div class="col-sm-3">
        <ul>
            @foreach($resp as $rp)

            <li class="">
                <span class="" href="#">{{$rp->nome}}</span>
                <ul class="">
                    @foreach($rp->aulasShow as $aula)
                    <li><a class="aulas" href="#" data-aula_id="{{$aula->id}}">{{$aula->sigla}}</a></li>
                    @endforeach
                </ul>

            </li>


            @endforeach
        </ul>
    </div>

    <div class="col-sm-9">
        
        <div id='calendar' ></div>
    </div>

</div>



@include('agenda.modal')
@endsection


@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('template/css/fullcalendar.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('template/js/plugins/fullcalendar.min.js') }}"></script>
<script src="{{ asset('js/agenda.js') }}"></script>
<script>



</script>
@endpush
