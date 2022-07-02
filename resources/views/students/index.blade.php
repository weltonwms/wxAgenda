@extends('layouts.app')

@section('breadcrumb')
    @breadcrumbs(['title'=>' Alunos', 'icon'=>'fa-graduation-cap', 'route'=>route('students.index'),'subtitle'=>'Gerenciamento de  Alunos'])

    @endbreadcrumbs
@endsection

@section('toolbar')
@toolbar
<a class="btn btn-sm btn-success mr-1 mb-1" href="{{route('students.create')}}" > <i class="fa fa-plus-circle"></i>Novo</a>
<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" data-type="link" data-route="{{url('students/{id}/edit')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-pencil"></i>Editar</button>
<button class="btn btn-sm btn-outline-danger mr-1 mb-1" type="button" data-type="delete" data-route="{{route('students_bath.destroy')}}" onclick="dataTableSubmit(event)"> <i class="fa fa-trash"></i>Excluir</button>
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
        <th>Módulo</th>
        <th>Cidade</th>
        <th>UF</th>
        <th>ID</th>
    </tr>
</thead>

<tbody>
   @foreach($students as $student)
    <tr>
        <td></td>
        <td><a href="{{route('students.edit', $student->id)}}">{{$student->nome}}</a></td>
        <td>{{$student->email}}</td>
        <td>{{$student->telefone}}</td>
        <td>{{$student->modulo_id}}</td>
        <td>{{$student->cidade}}</td>
        <td>{{$student->uf}}</td>
        <td>{{$student->id}}</td>
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
    Tabela.getInstance({colId:7}); //instanciando dataTable e informando a coluna do id
});
   //fim start Datatable//
</script>
@endpush

