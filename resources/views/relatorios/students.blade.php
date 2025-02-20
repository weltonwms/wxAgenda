@extends('layouts.app')
@section('breadcrumb')
@breadcrumbs(['title'=>'Relatório: Alunos - Presença', 'icon'=>'fa-circle-o',
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
                    <span class="text-primary d-block d-md-inline"><b>Mostrando {{$relatorio->items->count()}} Registro(s)</b></span>
                    @endif
                    <button class="btn btn-outline-success float-md-right"> Total Alunos:
                        {{$relatorio->total_alunos}}
                    </button>

                    @if($relatorio->total_alunos)
                    <button class="btn btn-outline-primary float-md-right mr-2" id="btnOpenEnviarEmail">
                        <i class="fa fa-envelope-o fa-lg" aria-hidden="true"></i> Enviar Email
                    </button>
                    @endif

                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <th width="1%">
                            @if($relatorio->total_alunos)
                            <div class="animated-checkbox">
                                <label>
                                    <input type="checkbox" id="check-all"><span class="label-text"></span>
                                </label>
                            </div>
                            @endif
                        </th>
                        <th width="2%">#</th>
                        <th width="15%">Cód Aluno</th>
                        <th>Nome Aluno</th>
                        <th>Qtd Células de Aula</th>

                       

                    </thead>

                    <tbody>
                        @foreach($relatorio->items as $key=>$item)
                        <tr>
                            <td>
                                <div class="animated-checkbox">
                                    <label>
                                        <input type="checkbox" class="check-item" data-id="{{$item->id}}">
                                        <span class="label-text"> </span>
                                    </label>
                                </div>
                            </td>
                            <td>{{++$key}}</td>
                            <td>{{$item->id}}</td>
                            <td>
                                <a target="_blank" href="{{route('students.edit', $item->id)}}">
                                    {{$item->nome}}
                                </a>
                            </td>
                            <td>{{$item->celulas_count}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

@include('messages.modal-bath')
@endsection

@push('scripts')
<script>
    
ckeckAllOnTable();

//início script Enviar Email.
configurarEnvioEmail("#btnOpenEnviarEmail", "#btnSubmitEnviarEmail", function() {
    return $(".check-item:checked").map(function() { return $(this).data("id"); }).get();
});

</script>

@endpush