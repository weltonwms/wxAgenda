@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>' Relatório: Alunos - Aulas', 'icon'=>'fa-circle-o', 'route'=>route('relatorio.students2'),
'subtitle'=>'Aulas Marcadas por Aluno'])

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
<?php

$modulesList->prepend('Todos','all');
?>



<div class="tile tile-nomargin">
{!! Form::open(['route'=>'relatorio.students2','id'=>'form_pesquisa'])!!}
        <div class='row'>
        <div class="col-sm-12">

            {!!Form::bsSelect('student_id', $studentsList,
                request('student_id'),
            ["class"=>"select2", "placeholder"=>"-Selecione-",
            "label"=>"Aluno"]
            )!!}
        </div>


            <div class="col-sm-2">

                {!!Form::bsSelect('module_id', $modulesList,
                    request('module_id'),
                ["class"=>"select2",
                "label"=>"Módulo"]
                )!!}
            </div>

            <div class="col-sm-2">

                {!!Form::bsSelect('teacher_id', $teachersList,
                request('teacher_id'),
                ["class"=>"select2",
                "placeholder"=>"-Selecione-","label"=>"Professor"]
                )!!}
            </div>


            <div class="col-sm-2">

                {!!Form::bsSelect('disciplina_id', $disciplinasList,
                request('disciplina_id'),
                ["class"=>"select2",
                "placeholder"=>"-Selecione-","label"=>"Disciplina"]
                )!!}
            </div>


            <div class="col-sm-3">
                {{ Form::bsDate('start', request('start'),['label'=>'Período >=', 
            'class'=>'form-control-sm']) }}
            </div>


            <div class="col-sm-3">
                {{ Form::bsDate('end', request('end'),['label'=>'Período <=', 
            'class'=>'form-control-sm']) }}
            </div>


        </div>

    </form>
</div>

<div class="tile">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Dia</th>
                    <th>Horário</th>
                    <th>Aula Sigla</th>
                    <th>Módulo</th>
                    <th>Disciplina</th>
                    <th>Professor</th>
                   
                </tr>
            </thead>
            <tbody>
                @foreach($celulas as $celula)
                <tr>
                    <td>{{$celula->getDiaFormatado()}}</td>
                    <td>{{$celula->horario}}</td>
                    <td>{{$celula->aula_sigla}}</td>
                    <td>{{$celula->module_nome}}</td>
                    <td>{{$celula->disciplina_nome}}</td>
                    <td>{{$celula->teacher_nome}}</td>
                   
                </tr>

                @endforeach

            </tbody>
        </table>
    </div>
</div>




@endsection

