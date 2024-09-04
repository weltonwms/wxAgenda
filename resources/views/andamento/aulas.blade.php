@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>' Andamento Aulas', 'icon'=>'fa-battery-2', 'route'=>route('andamento_aulas'),
'subtitle'=>'Andamento das Aulas'])
@endbreadcrumbs
@endsection


@section('content')

<div class="tile tile-nomargin">

    <div class="form-inline">
        <div class="form-group">
            <label class="text-primary mr-1" title="Clique no nº para Detalhes">Créditos Atuais:</label>
            <button type="button" 
                class="creditos_atuais btn btn-outline-info btn-sm mr-3"> 
                {{$student->saldo_atual}}
                <i class="fa fa-caret-up ms-2 mb-2" aria-hidden="true"></i>
            </button>
        </div>

        <div class="form-group">
            <label class="text-primary mr-1">Módulo Corrente:</label>
            <button type="button" class="btn btn-outline-info btn-sm mr-3">
                @if($student->module){{$student->module->nome}}@endif</button>


        </div>
    </div>

</div>


<div class="tile tile-nomargin">
    <form action="{{route('andamento_aulas')}}">
        <div class='row'>
            <div class="col-sm-2">
                {!!Form::bsSelect('module_id', $modulesList,
                $requestModuleId,
                ['onchange'=>"this.form.submit()","class"=>"select2",
                "label"=>"Módulo"]
                )!!}
            </div> 
            <div class="col-sm-2">
                {!!Form::bsSelect('disciplina_id', $disciplinasList,
                request('disciplina_id'),
                ['onchange'=>"this.form.submit()","class"=>"select2",
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
                    
                    <th>Aluno</th>
                    <th>Total Aulas</th>
                    <th>Aulas Feitas</th>
                    <th>% Completado</th> 
                    <th>Detalhes</th>    
                </tr>
            </thead>
            <tbody>
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
            </tbody>
        </table>
    </div>
</div>

@endsection

@include('andamento.modal')