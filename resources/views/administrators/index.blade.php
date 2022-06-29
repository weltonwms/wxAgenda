@extends('layouts.app')

@section('breadcrumb')
    @breadcrumbs(['title'=>' Admnistradores', 'icon'=>'fa-user-secret', 'route'=>route('administrators.index'),'subtitle'=>'Gerenciamento de  Administradores'])

    @endbreadcrumbs
@endsection

@section('toolbar')
@toolbar
<a class="btn btn-sm btn-success mr-1 mb-1" href="{{route('administrators.create')}}" > <i class="fa fa-plus-circle"></i>Novo</a>
<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" data-type="link" data-route="{{url('administrators/{id}/edit')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-pencil"></i>Editar</button>
<button class="btn btn-sm btn-outline-danger mr-1 mb-1" type="button" data-type="delete" data-route="{{route('administrators_bath.destroy')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-trash"></i>Excluir</button>
@endtoolbar
@endsection

@section('content')
@datatables
<thead>
    <tr>
        <th width="4%"><input class="checkall" type="checkbox"></th>
        <th>Nome</th>
        
        <th>ID</th>
    </tr>
</thead>

<tbody>
   @foreach($administrators as $administrator)
    <tr>
        <td></td>
        <td><a href="{{route('administrators.edit', $administrator->id)}}">{{$administrator->nome}}</a></td>
       
        <td>{{$administrator->id}}</td>
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

