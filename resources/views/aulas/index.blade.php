@extends('layouts.app')

@section('breadcrumb')
    @breadcrumbs(['title'=>' Aulas', 'icon'=>'fa-file-text', 'route'=>route('aulas.index'),'subtitle'=>'Gerenciamento de  Aulas'])

    @endbreadcrumbs
@endsection

@section('toolbar')
@toolbar
<a class="btn btn-sm btn-success mr-1 mb-1" href="{{route('aulas.create')}}" > <i class="fa fa-plus-circle"></i>Novo</a>
<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" data-type="link" data-route="{{url('aulas/{id}/edit')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-pencil"></i>Editar</button>
<button class="btn btn-sm btn-outline-danger mr-1 mb-1" type="button" data-type="delete" data-route="{{route('aulas_bath.destroy')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-trash"></i>Excluir</button>
@endtoolbar
@endsection

@section('content')
@datatables
<thead>
    <tr>
        <th width="4%"><input class="checkall" type="checkbox"></th>
        <th>Sigla</th>
        <th>Módulo</th>
        <th>Disciplina</th>
        <th>Ordem</th>
        <th>ID</th>
    </tr>
</thead>

<tbody>
   @foreach($aulas as $aula)
    <tr>
        <td></td>
        <td><a href="{{route('aulas.edit', $aula->id)}}">{{$aula->sigla}}</a></td>
        <td>{{$aula->module->nome}}</td>
        <td>{{$aula->disciplina->nome}}</td>
        <td>{{$aula->ordem}}</td>
        <td>{{$aula->id}}</td>
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

