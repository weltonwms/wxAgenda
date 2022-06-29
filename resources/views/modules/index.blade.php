@extends('layouts.app')

@section('breadcrumb')
    @breadcrumbs(['title'=>' Módulos', 'icon'=>'fa-cubes', 'route'=>route('modules.index'),'subtitle'=>'Gerenciamento de  Módulos'])

    @endbreadcrumbs
@endsection

@section('toolbar')
@toolbar
<a class="btn btn-sm btn-success mr-1 mb-1" href="{{route('modules.create')}}" > <i class="fa fa-plus-circle"></i>Novo</a>
<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" data-type="link" data-route="{{url('modules/{id}/edit')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-pencil"></i>Editar</button>
<button class="btn btn-sm btn-outline-danger mr-1 mb-1" type="button" data-type="delete" data-route="{{route('modules_bath.destroy')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-trash"></i>Excluir</button>
@endtoolbar
@endsection

@section('content')
@datatables
<thead>
    <tr>
        <th><input class="checkall" type="checkbox"></th>
        <th width="90%">Nome</th>
        <th>ID</th>
    </tr>
</thead>

<tbody>
   @foreach($modules as $module)
    <tr>
        <td></td>
        <td><a href="{{route('modules.edit', $module->id)}}">{{$module->nome}}</a></td>
        <td>{{$module->id}}</td>
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

