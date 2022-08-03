@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>' Aulas Agendadas', 'icon'=>'fa-calendar', 'route'=>route('agendados.index'),
'subtitle'=>'Aulas Agendadas'])

@endbreadcrumbs
@endsection


@section('content')
<div class="tile tile-nomargin">
    <form action="{{route('agendados.index')}}">
        <label class="text-primary">Módulo</label>
        {!!Form::select('module_id', $modulesList,
        request('module_id'),
        ['onchange'=>"this.form.submit()","class"=>"select2","placeholder"=>"-Selecione-"]
        )!!}
        &nbsp;&nbsp;

        <label class="text-primary">Professores</label>
        {!!Form::select('teacher_id', $teachersList,
        request('teacher_id'),
        ['onchange'=>"this.form.submit()","class"=>"select2","placeholder"=>"-Selecione-"]
        )!!}
        &nbsp;&nbsp;


        <label class="text-primary">Disciplina</label>
        {!!Form::select('disciplina_id', $disciplinasList,
        request('disciplina_id'),
        ['onchange'=>"this.form.submit()","class"=>"select2","placeholder"=>"-Selecione-"]
        )!!}
        &nbsp;&nbsp;

        {{ Form::bsDate('start', request('start'),['label'=>'Período >=', 
            'class'=>'form-control-sm','onchange'=>"this.form.submit()"]) }}

            {{ Form::bsDate('end', request('end'),['label'=>'Período <=', 
            'class'=>'form-control-sm','onchange'=>"this.form.submit()"]) }}

        
       

    </form>
</div>

<div class="tile">

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
                <td>{{$celula->dia}}</td>
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


@endsection