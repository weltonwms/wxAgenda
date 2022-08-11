@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>' Agendar Aula', 'icon'=>'fa-calendar', 'route'=>route('agenda.index'),'subtitle'=>'Agendamento de
Aulas'])

@endbreadcrumbs
@endsection


@section('content')


<div class="tile row">
<input type="hidden"  id="aula_id" value="" class="form-control">
<input type="hidden" id="horarios_validos" value="{{$horariosList}}" class="form-control">
    <div class="col-sm-3" id="jstree_list_aulas">
        
    </div>

    <div class="col-sm-9">
        
        <div id='calendar' ></div>
    </div>

</div>



@include('agenda.modal')
@endsection


@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('template/css/fullcalendar.min.css') }}">
<link rel="stylesheet" href="{{ asset('template/css/jstree/jstree.min.css') }}" />
@endpush

@push('scripts')
<script src="{{ asset('template/js/plugins/fullcalendar.min.js') }}"></script>
<script src="{{ asset('template/js/plugins/jstree.min.js') }}"></script>
<script src="{{ asset('js/agenda.js') }}"></script>
<script>

$('#jstree_list_aulas').jstree({
  'core' : {
    'data' : {
      'url' : asset+'aulasToAgenda',
      'data' : function (node) {
        return { 'id' : node.id };
      }
    }
  }
});


</script>
@endpush
