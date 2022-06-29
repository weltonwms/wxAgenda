@extends('layouts.app')

@section('breadcrumb')
    @breadcrumbs(['title'=>' Professores', 'icon'=>'fa-blind', 'route'=>route('teachers.index'),'subtitle'=>'Gerenciamento de  Professores'])

    @endbreadcrumbs
@endsection

@section('toolbar')
@toolbar
<a class="btn btn-sm btn-success mr-1 mb-1" href="{{route('teachers.create')}}" > <i class="fa fa-plus-circle"></i>Novo</a>
<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" data-type="link" data-route="{{url('teachers/{id}/edit')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-pencil"></i>Editar</button>
<button class="btn btn-sm btn-outline-danger mr-1 mb-1" type="button" data-type="delete" data-route="{{route('teachers_bath.destroy')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-trash"></i>Excluir</button>
@endtoolbar
@endsection

@section('content')
@datatables
<thead>
    <tr>
        <th width="4%"><input class="checkall" type="checkbox"></th>
        <th>Nome</th>
        <th>Email</th>
        <th>Telefone</th>
        <th>ID</th>
    </tr>
</thead>

<tbody>
   @foreach($teachers as $teacher)
    <tr>
        <td></td>
        <td><a href="{{route('teachers.edit', $teacher->id)}}">{{$teacher->nome}}</a></td>
        <td>{{$teacher->email}}</td>
        <td>{{$teacher->telefone}}</td>
        <td>{{$teacher->id}}</td>
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
    Tabela.getInstance({colId:4}); //instanciando dataTable e informando a coluna do id
});
   //fim start Datatable//
</script>
@endpush

