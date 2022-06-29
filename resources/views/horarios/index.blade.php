@extends('layouts.app')

@section('breadcrumb')
    @breadcrumbs(['title'=>' Horários', 'icon'=>'fa-clock-o', 'route'=>route('horarios.index'),'subtitle'=>'Gerenciamento de  Horários'])

    @endbreadcrumbs
@endsection

@section('toolbar')
@toolbar
<a class="btn btn-sm btn-success mr-1 mb-1" href="{{route('horarios.create')}}" > <i class="fa fa-plus-circle"></i>Novo</a>
<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" data-type="link" data-route="{{url('horarios/{id}/edit')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-pencil"></i>Editar</button>
<button class="btn btn-sm btn-outline-danger mr-1 mb-1" type="button" data-type="delete" data-route="{{route('horarios_bath.destroy')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-trash"></i>Excluir</button>
@endtoolbar
@endsection

@section('content')
@datatables
<thead>
    <tr>
        <th width="3%"><input class="checkall" type="checkbox"></th>
        <th>Turno</th>
        <th>Horário</th>
       
    </tr>
</thead>

<tbody>
   @foreach($horarios as $horario)
    <tr>
        <td></td>
        <td><a href="{{route('horarios.edit', $horario->horario)}}">{{$horario->getNomeTurno()}}</a></td>
        <td>{{$horario->horario}}</td>
    </tr>
    @endforeach
</tbody>
@enddatatables
@endsection

@push('scripts')

<script>
    /*
     * First start on Table
     * **********************************
     */
$(document).ready(function() {
    Tabela.getInstance({colId:2}); //instanciando dataTable e informando a coluna do id
});
   //fim start Datatable//
</script>
@endpush

