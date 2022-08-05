@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>' Aulas Agendadas', 'icon'=>'fa-calendar', 'route'=>route('agendados.index'),
'subtitle'=>'Aulas Agendadas'])

@endbreadcrumbs
@endsection


@section('content')
<div class="tile tile-nomargin">
    <form action="{{route('agendados.index')}}">
        <div class='row'>
            <div class="col-sm-2">

                {!!Form::bsSelect('module_id', $modulesList,
                request('module_id'),
                ['onchange'=>"this.form.submit()","class"=>"select2",
                "placeholder"=>"-Selecione-","label"=>"Módulo"]
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