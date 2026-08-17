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



<div class="tile tile-nomargin">
    <form action="{{route('aulas.index')}}">
        <div class='row'>
            <div class="col-xl-2 col-md-4">

                <x-form.select
                    name="module_id"
                    :list="$modulesList"
                    :value="request('module_id')"
                    label="Filtro Módulo"
                    placeholder="-Selecione-"
                    class="select2"
                    style="width:100%"
                    onchange="this.form.submit()"
                />
            </div>

           
            <div class="col-xl-2 col-md-4">

                <x-form.select
                    name="disciplina_id"
                    :list="$disciplinasList"
                    :value="request('disciplina_id')"
                    label="Filtro Disciplina"
                    placeholder="-Selecione-"
                    class="select2"
                    style="width:100%"
                    onchange="this.form.submit()"
                />
            </div>

            <div class="col-xl-8 col-md-4">
                <div class="form-group float-md-right">
                    <label class="control-label d-none d-md-block"><i class="fa fa-list-ol" aria-hidden="true"></i></label>
                <a class="btn btn-secondary form-control"
                href="{{route('showSystemCounter')}}">
                    System Counter
                </a>
                </div>
               
            </div>



        </div>



    </form>
</div>







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

