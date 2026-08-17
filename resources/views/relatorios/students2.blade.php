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
<form method="POST" action="{{ route('relatorio.students2') }}" id="form_pesquisa">
        @csrf
        <div class='row'>
        <div class="col-sm-12">

            <x-form.select name="student_id" :list="$studentsList" :value="request('student_id')" label="Aluno" placeholder="-Selecione-" class="select2" />
        </div>


            <div class="col-sm-2">

                <x-form.select name="module_id" :list="$modulesList" :value="request('module_id')" label="Módulo" class="select2" />
            </div>

            <div class="col-sm-2">

                <x-form.select name="teacher_id" :list="$teachersList" :value="request('teacher_id')" label="Professor" placeholder="-Selecione-" class="select2" />
            </div>


            <div class="col-sm-2">

                <x-form.select name="disciplina_id" :list="$disciplinasList" :value="request('disciplina_id')" label="Disciplina" placeholder="-Selecione-" class="select2" />
            </div>


            <div class="col-sm-3">
                <x-form.date name="start" :value="request('start')" label="Período >=" class="form-control-sm" />
            </div>


            <div class="col-sm-3">
                <x-form.date name="end" :value="request('end')" label="Período <=" class="form-control-sm" />
            </div>

            <div class="col-sm-2">
                <x-form.select
                    name="ordenado_por"
                    :list="[1 => 'Dia/Horário', 2 => 'Aula Sigla', 3 => 'Nome Professor']"
                    :value="request('ordenado_por')"
                    label="Ordenado Por"
                    class="select2"
                />
            </div>

            <div class="col-sm-2">
                <x-form.select
                    name="ordem"
                    :list="[1 => 'Decrescente', 2 => 'Crescente']"
                    :value="request('ordem')"
                    label="Ordem"
                    class="select2"
                />
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

