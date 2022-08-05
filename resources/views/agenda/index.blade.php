@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>' Marcar Aula', 'icon'=>'fa-calendar', 'route'=>route('agenda.index'),'subtitle'=>'Marcação de
Aulas'])

@endbreadcrumbs
@endsection


@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<input type="text"  id="aula_id" value="" class="form-control">
<div class="tile row">

    <div class="col-sm-3" id="jstree_demo_div">
        
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<script src="{{ asset('js/agenda.js') }}"></script>
<script>

$('#jstree_demo_div').jstree({
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
