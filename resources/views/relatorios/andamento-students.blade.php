@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>' Relatório: Alunos - Andamento', 'icon'=>'fa-circle-o', 'route'=>route('relatorio.andamento'),
'subtitle'=>'Andamento das Aulas pelos Alunos'])

@endbreadcrumbs
@endsection

@section('toolbar')
@toolbar
<button class="btn btn-sm btn-primary mr-1 mb-1" onclick="document.getElementById('form_pesquisa').submit()">
    <i class="fa fa-search" aria-hidden="true"></i> Executar Pesquisa
</button>
<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" onclick="limparFormPesquisa()">
    <i class="fa fa-undo"></i>Limpar Form Pesquisa
</button>
@endtoolbar
@endsection


@section('content')

<div class="tile tile-nomargin">
{!! Form::open(['route'=>'relatorio.andamento','id'=>'form_pesquisa'])!!}
        <div class='row'>

            <div class="col-sm-2">

                {!!Form::bsSelect('module_id', $modulesList,
                    request('module_id'),
                    ["class"=>"select2",
                    "label"=>"Módulo"]
                )!!}
            </div>           


            <div class="col-sm-2">

                {!!Form::bsSelect('disciplina_id', $disciplinasList,
                request('disciplina_id'),
                ["class"=>"select2",
                "placeholder"=>"-Selecione-","label"=>"Disciplina"]
                )!!}
            </div>

        </div>
    </form>
</div>

<div class="tile">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Aluno</th>
                    <th>Total Aulas</th>
                    <th>Aulas Feitas</th>
                    <th>% Completado</th>    
                </tr>
            </thead>
            <tbody>
                @foreach($relatorio->getAlunos() as $key=>$aluno)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$aluno->nome}}</td>
                        <td>{{$aluno->countAulas}}</td>
                        <td>{{$aluno->countFeitas}}</td>
                        <td class="@if($aluno->percentualComplete > 99) bg-success text-white @endif">
                        {{number_format($aluno->percentualComplete,0)}}%
                        </td>
                    </tr>
                @endforeach     
            </tbody>
        </table>
    </div>
</div>

@endsection

