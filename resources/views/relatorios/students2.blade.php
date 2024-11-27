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

            <div class="col-sm-2">
                {!!Form::bsSelect('ordenado_por', [1=>"Dia/Horário", 2=>"Aula Sigla", 3=> 'Nome Professor'],
                request('ordenado_por'),
                ["class"=>"select2",
                "label"=>"Ordenado Por"]
                )!!}
            </div>

            <div class="col-sm-2">
                {!!Form::bsSelect('ordem', [1=>"Decrescente", 2=>"Crescente"],
                request('ordem'),
                ["class"=>"select2",
                "label"=>"Ordem"]
                )!!}
            </div>


        </div>

    </form>

    @if($student && is_numeric(request('module_id')))
    <?php $andamento = $student->getAndamento(request('module_id'),request('disciplina_id'));?>

    <div class="shadow-sm" style="background-color: #e4f4f7 ">
        <h4 class="text-center" style="text-decoration:underline">Andamento de {{$student->nome}}</h4>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>                   
                   
                    <th>Total Aulas</th>
                    <th>Aulas Feitas</th>
                    <th>% Completado</th> 
                    <th>Detalhes</th>    
                </tr>
            </thead>
            <tbody>              
                    <tr>
                      
                       <td>{{$andamento->countAulas}}</td>
                       <td>{{$andamento->countFeitas}}</td>
                       <td class="@if($andamento->percentualComplete > 99) bg-success text-white @endif"> 
                           {{number_format($andamento->percentualComplete,0)}}% 
                       </td>
                       <td>
                           <a href="#" class="detalhes_andamento"
                           data-student_id ="{{$student->id}}"
                           data-nome="{{$student->nome}}"
                           data-detalhes="{{base64_encode($andamento->mapeamento->toJson() )}}"
                           >
                               <i class="fa fa-eye"></i>
                           </a>                           
                       </td>
                   </tr>
            </tbody>
        </table>  
    </div>
    @include('andamento.modal')
    @endif

</div> <!--fechamento first tile -->

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
                    <td>
                        <a href="#" class="showCelula" data-celula_id="{{$celula->id}}" title="Visualizar Célula">
                            {{$celula->aula_sigla}}
                        </a>
                    </td>
                    <td>{{$celula->module_nome}}</td>
                    <td>{{$celula->disciplina_nome}}</td>
                    <td>{{$celula->teacher_nome}}</td>
                   
                </tr>

                @endforeach

            </tbody>
        </table>
    </div>
</div>


@include('grade.modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/showDetailCelula.js') }}"></script>
@endpush

