@extends('layouts.app')

@section('breadcrumb')
    @breadcrumbs(['title'=>' Disciplinas', 'icon'=>'fa-book', 'route'=>route('disciplinas.index'),'subtitle'=>'Gerenciamento de  Disciplinas'])

    @endbreadcrumbs
@endsection

@section('toolbar')
@toolbar
<a class="btn btn-sm btn-success mr-1 mb-1" href="{{route('disciplinas.create')}}" > <i class="fa fa-plus-circle"></i>Novo</a>
<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" data-type="link" data-route="{{url('disciplinas/{id}/edit')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-pencil"></i>Editar</button>
<button class="btn btn-sm btn-outline-danger mr-1 mb-1" type="button" data-type="delete" data-route="{{route('disciplinas_bath.destroy')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-trash"></i>Excluir</button>
@endtoolbar
@endsection

@section('content')
@datatables
<thead>
    <tr>
        <th><input class="checkall" type="checkbox"></th>
        <th width="85%">Nome</th>
        <th>Base</th>
        <th>ID</th>
    </tr>
</thead>

<tbody>
   @foreach($disciplinas as $disciplina)
    <tr>
        <td></td>
        <td><a href="{{route('disciplinas.edit', $disciplina->id)}}">{{$disciplina->nome}}</a></td>
        <td>{{$disciplina->base}}</td>
        <td>{{$disciplina->id}}</td>
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
    Tabela.getInstance({colId:3}); //instanciando dataTable e informando a coluna do id
});
   //fim start Datatable//
</script>
@endpush

