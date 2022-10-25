@extends('layouts.app')



@section('breadcrumb')
<div class="app-title">
    <div>
        <h1><i class="fa fa-list-ol"></i> System Counter </h1>
        <p>System Counter</p>
    </div>

    <ul class="app-breadcrumb breadcrumb side">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item active"><a href="{{route('aulas.index')}}">Aulas</a></li>
        <li class="breadcrumb-item">System Conter</li>
       
    </ul>
</div>

@endsection



@section('content')
<div class="row">


</div>
<div class="row tile">
    <div class="col-md-12">
        <a class="btn btn-primary pull-right" href="{{route('aulas.index')}}">
            <i class="fa fa-reply"></i>Voltar p/ Aulas
        </a>
    </div>
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th>Módulo</th>
                    <th>Disciplina</th>
                    <th>Contador</th>
                    <th>Atualizado em</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registros as $registro)
                <tr>
                    <td>{{$registro->module->nome}}</td>
                    <td>{{$registro->disciplina->nome}}</td>
                    <td>{{$registro->contador}}</td>
                    <td>{{$registro->updatedAtFormated()}}</td>
                </tr>

                @endforeach

            </tbody>

        </table>
    </div>
    <div class="col-md-12 bg-secondary mt-5">
        <p class="text-center text-white p-2">Observação: A disciplina/Módulo não presente
            na tabela possui contador=1.
        </p>
    </div>

</div>

@endsection