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

            <div class="col-sm-12">
                {!!Form::bsSelect('student_id', $studentsList,
                request('student_id'),
                ["class"=>"select2", "placeholder"=>"-Selecione-",
                "label"=>"Aluno"]
                )!!}
                <small style="margin-top:-1rem; margin-bottom:0.7rem;" 
                    class="form-text text-muted">
                    Não Selecione Aluno se quiser ver todos de seus módulos Correntes
                </small>

            </div>

            <div class="col-sm-2">

                {!!Form::bsSelect('module_id', $modulesList,
                    $requestModuleId,
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
                    @if(!$student)
                        <th>#</th>
                    @endif
                    <th>Aluno</th>
                    <th>Total Aulas</th>
                    <th>Aulas Feitas</th>
                    <th>% Completado</th> 
                    <th>Detalhes</th>    
                </tr>
            </thead>
            <tbody>
                @if($student)
                    <?php $andamento = $student->getAndamento($requestModuleId,request('disciplina_id'));?>
                    <tr>
                       <td>{{$student->nome}}</td>
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
                @else
                    @foreach($relatorio->getAlunos() as $key=>$aluno)
                        <tr>
                            <td>{{++$key}}</td>
                            <td>{{$aluno->nome}}</td>
                            <td>{{$aluno->countAulas}}</td>
                            <td>{{$aluno->countFeitas}}</td>
                            <td class="@if($aluno->percentualComplete > 99) bg-success text-white @endif">
                            {{number_format($aluno->percentualComplete,0)}}%
                            </td>
                            <td>
                                <a href="#" class="detalhes_andamento"
                                data-student_id ="{{$aluno->id}}"
                                data-nome="{{$aluno->nome}}"
                                data-detalhes="{{base64_encode($relatorio->mapeamento($aluno->aulasTarget)->toJson() )}}"
                                >
                                <i class="fa fa-eye"></i>
                                </a>                           
                            </td>
                        </tr>                
                    @endforeach                 
                @endif

            </tbody>
        </table>
    </div>
</div>
@endsection

@include('andamento.modal')