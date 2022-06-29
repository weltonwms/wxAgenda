@extends('layouts.app')

@section('breadcrumb')
    @breadcrumbs(['title'=>' Restrições', 'icon'=>'fa-lock', 'route'=>route('restrictions.index'),'subtitle'=>'Gerenciamento de  Restrições'])

    @endbreadcrumbs
@endsection

@section('toolbar')
@toolbar
<a class="btn btn-sm btn-success mr-1 mb-1" href="{{route('restrictions.create')}}" > <i class="fa fa-plus-circle"></i>Novo</a>
<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" data-type="link" data-route="{{url('restrictions/{id}/edit')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-pencil"></i>Editar</button>
<button class="btn btn-sm btn-outline-danger mr-1 mb-1" type="button" data-type="delete" data-route="{{route('restrictions_bath.destroy')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-trash"></i>Excluir</button>
@endtoolbar
@endsection

@section('content')
@datatables
<thead>
    <tr>
        <th width="4%"><input class="checkall" type="checkbox"></th>
        <th>Módulo</th>
        <th>Disciplina</th>
        <th>Nível Início</th>
        <th>Nível Fim</th>
        <th>ID</th>
    </tr>
</thead>

<tbody>
   @foreach($restrictions as $restriction)
    <tr>
        <td></td>
        <td><a href="{{route('restrictions.edit', $restriction->id)}}">{{$restriction->module->nome}}</a></td>
        <td>{{$restriction->disciplina->nome}}</td>
        <td>{{$restriction->level_start}}</td>
        <td>{{$restriction->level_end}}</td>
        <td>{{$restriction->id}}</td>
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
    Tabela.getInstance({colId:5}); //instanciando dataTable e informando a coluna do id
});
   //fim start Datatable//
</script>
@endpush

