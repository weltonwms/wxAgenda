@extends('layouts.app')
@section('breadcrumb')
@breadcrumbs(['title'=>'Relatório: Alunos', 'icon'=>'fa-circle-o',
'route'=>route('relatorio.students'),'subtitle'=>'Atividades de Agendamento'])

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
            {!! Form::open(['route'=>'relatorio.students','id'=>'form_pesquisa'])!!}
            <div class="row">
                <div class="col-md-3">
                    {{ Form::bsDate('periodo_inicio', request('periodo_inicio'),['label'=>'Período Início >=']) }}
                </div>
                <div class="col-md-3">
                    {{ Form::bsDate('periodo_fim',request('periodo_fim'),['label'=>'Período Fim <=']) }}
                </div>

                <div class="col-md-4">
                    {{ Form::bsSelect('atividade',[0=>'Inativo no Período',1=>'Ativo no Período'],
                        request('atividade'),['label'=>"Atividade Agendamento", 
                    'class'=>'select2']) }}
                </div>

                
            </div>
            </form>

            <div class="row">
                <div class="col-md-12 ">
                    @if($relatorio->items)
                    <span class="text-primary"><b>Mostrando {{$relatorio->items->count()}} Registro(s)</b></span>
                    @endif
                    <button class="btn btn-outline-success pull-right"> Total Alunos:
                        {{$relatorio->total_alunos}}
                    </button>
                   
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <th>#</th>
                        <th width="15%">Cód Aluno</th>
                        <th>Nome Aluno</th>
                        <th>Qtd Células de Aula</th>

                       

                    </thead>

                    <tbody>
                        @foreach($relatorio->items as $key=>$item)
                        <tr>
                            <td>{{++$key}}</td>
                            <td>{{$item->id}}</td>
                            <td>{{$item->nome}}</td>
                            <td>{{$item->celulas_count}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

@endsection