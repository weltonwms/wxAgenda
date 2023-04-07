@extends('layouts.app')
@section('breadcrumb')
@breadcrumbs(['title'=>'Relatório: Professores', 'icon'=>'fa-circle-o',
'route'=>route('relatorio.teachers'),'subtitle'=>'Horas Trabalhadas por Professores'])

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

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            {!! Form::open(['route'=>'relatorio.teachers','id'=>'form_pesquisa'])!!}
            <div class="row">
                <div class="col-md-3">
                    {{ Form::bsDate('periodo_inicio', request('periodo_inicio'),['label'=>'Período Início >=']) }}
                </div>
                <div class="col-md-3">
                    {{ Form::bsDate('periodo_fim',request('periodo_fim'),['label'=>'Período Fim <=']) }}
                </div>

                <div class="col-md-4">
                    {{ Form::bsSelect('teacher_id',$teachers,request('teacher_id'),['label'=>"Professor", 
                    'class'=>'select2',"placeholder"=>"-Selecione-"]) }}
                </div>
            </div>
            </form>

            <div class="row">
                <div class="col-md-12 ">
                    @if($relatorio->items)
                    <span class="text-primary"><b>Mostrando {{$relatorio->items->count()}} Registro(s)</b></span>
                    @endif
                    <button class="btn btn-outline-success pull-right"> Total Horas/Células:
                        {{$relatorio->total_celulas}}
                    </button>
                   
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <th>#</th>
                        <th width="15%">Cód Professor</th>
                        <th width="30%">Nome</th>
                        <th>Dia</th>
                        <th>Horário</th>
                        <th>Qtd Alunos</th>

                       

                    </thead>

                    <tbody>
                        @foreach($relatorio->items as $key=>$item)
                        <tr>
                            <td>{{++$key}}</td>
                            <td>{{$item->teacher_id}}</td>
                            <td>{{$item->teacher_nome}}</td>
                            <td>{{$item->getDiaFormatado()}}</td>
                            <td>{{$item->horario}}</td>
                            <td>{{$item->students_count}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

@endsection