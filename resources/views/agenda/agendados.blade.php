@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>' Aulas Marcadas', 'icon'=>'fa-calendar-check-o', 'route'=>route('agendados.index'),
'subtitle'=>'Aulas Marcadas'])

@endbreadcrumbs
@endsection


@section('content')
<?php
$requestModuleId=request('module_id');
if(!$requestModuleId):
    //pesquisa padrao == current module do student
    //pesquisa padrao modificada para == todos
    //$requestModuleId=$student->module_id;
endif;
$modulesList->prepend('Todos','all');
?>

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

        <div class="form-group">
            <label class="text-primary mr-1">Desmarcações no Mês:</label>
            <button type="button" class="btn btn-outline-info btn-sm mr-3">
                {{$student->countCancellationsByMonth()}}
            </button>

        </div>

        <div class="form-group">
            <label class="text-primary mr-1">Limite Desmarcações no Mês:</label>
            <button type="button" class="btn btn-outline-info btn-sm mr-3">
                {{$limitDesmarcacao}}
            </button>

        </div>
        
    </div>



</div>


<div class="tile tile-nomargin">
    <form action="{{route('agendados.index')}}">
        <div class='row'>
            <div class="col-sm-2">

                {!!Form::bsSelect('module_id', $modulesList,
                $requestModuleId,
                ['onchange'=>"this.form.submit()","class"=>"select2",
                "label"=>"Módulo"]
                )!!}
            </div>

            <div class="col-sm-2">

                {!!Form::bsSelect('teacher_id', $teachersList,
                request('teacher_id'),
                ['onchange'=>"this.form.submit()","class"=>"select2",
                "placeholder"=>"-Selecione-","label"=>"Professor"]
                )!!}
            </div>


            <div class="col-sm-2">

                {!!Form::bsSelect('disciplina_id', $disciplinasList,
                request('disciplina_id'),
                ['onchange'=>"this.form.submit()","class"=>"select2",
                "placeholder"=>"-Selecione-","label"=>"Disciplina"]
                )!!}
            </div>


            <div class="col-sm-3">
                {{ Form::bsDate('start', request('start'),['label'=>'Período >=', 
            'class'=>'form-control-sm','onchange'=>"this.form.submit()"]) }}
            </div>


            <div class="col-sm-3">
                {{ Form::bsDate('end', request('end'),['label'=>'Período <=', 
            'class'=>'form-control-sm','onchange'=>"this.form.submit()"]) }}
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
                    <th>Ação</th>
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
                    <td>
                        <a href="#" class="showCelula" data-celula_id="{{$celula->id}}" title="Visualizar Célula">
                            <i class="fa fa-commenting-o"></i> 
                        </a>

                        @if($celula->isOnLimitHoursToStart())
                        <a href="#" class="desmarcarAula text-danger" title="Desmarcar" data-celula_id="{{$celula->id}}"
                            data-aula_sigla="{{$celula->aula_sigla}}" data-horario="{{$celula->horario}}"
                            data-dia="{{$celula->getDiaFormatado()}}" data-teacher_nome="{{$celula->teacher_nome}}">
                            <i class="fa fa-trash"></i>    
                        </a>
                        @endif
                       
                    </td>
                </tr>

                @endforeach

            </tbody>
        </table>
    </div>
</div>

<form id="desmarcar-aula-form" action="" data-route="{{ route('agendados.desmarcar','celula_id') }}" method="POST"
    style="display: none;">
    @csrf
    <input type='hidden' name='_method' value='DELETE'>
</form>
@include('grade.modal')

@endsection

@push('scripts')
<script>
function desmarcarAula(event) {
    event.preventDefault();
    var target = event.currentTarget;
    var dados = target.dataset;
    var content = 'Aula: ' + dados.aula_sigla + '; ' +
        'Dia: ' + dados.dia + ' ; Horário: ' + dados.horario +
        '\n Professor: ' + dados.teacher_nome;
    wxConfirm(function() {
        var form = $('#desmarcar-aula-form');
        var route = form.attr('data-route');
        route = route.replace('celula_id', dados.celula_id);
        form.attr('action', route);
        console.log(route);
        form.submit();
    }, "Deseja Realmente Desmarcar?", content);
}
$('.desmarcarAula').click(desmarcarAula);
</script>
<script src="{{ asset('js/showDetailCelula.js') }}"></script>

@endpush