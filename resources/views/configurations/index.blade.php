@extends('layouts.app')

@section('content')
<div class="container tile">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Configurações Globais</div>

                <div class="card-body">
                    <div class="form-row">

                        {!! Form::open(['route'=>'configurations.save','class'=>'','id'=>'ConfigurationForm'])!!}
                        <div class="col-md-12">
                            <label for="" class="control-label yesno">Agendamento Ativo</label>
                            {{ Form::bsYesno('agendamento_ativo',$configuracoes['agendamento_ativo']) }}
                        </div>
                        <div class="col-md-12 mt-3">

                            {{ Form::bsNumber('celula_limit',$configuracoes['celula_limit'],['label'=>"Limite de Alunos por Célula de Aula",'min'=>'1']) }}
                        </div>

                        <div class="col-md-12 mt-3">
                            <label for="" class="control-label yesno">Desmarcação Permitida</label>
                            {{ Form::bsYesno('desmarcacao_permitida',$configuracoes['desmarcacao_permitida']) }}
                        </div>


                        <div class="col-md-12 mt-3 bloco_desmarcacao_permitida">

                            {{ Form::bsNumber('desmarcacao_limit_by_month',$configuracoes['desmarcacao_limit_by_month'],
                            ['label'=>"Limite de Desmarcação de Aulas por Mês",'min'=>'0']) }}
                        </div>

                        <div class="col-md-12 mt-3 bloco_desmarcacao_permitida">

                            {{ Form::bsNumber('desmarcacao_hours_before',$configuracoes['desmarcacao_hours_before'],
                            ['label'=>"Horas de Antecedência para desmarcar Aula",'min'=>'0']) }}
                        </div>

                        <div class="col-md-12 mt-5">
                            <button type="submit" class="btn btn-primary">Salvar Configurações</button>
                        </div>

                        {!! Form::close() !!}
                    </div>






                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function onDesmarcacaoPermitida(event) {
    var permitida = !!parseInt($("[name=desmarcacao_permitida]:checked").val()); //conversão para boleano
    if (permitida) {
        $(".bloco_desmarcacao_permitida").slideDown();;
    } else {
        $(".bloco_desmarcacao_permitida").slideUp();;

    }
}
$("[name=desmarcacao_permitida]").on("change", onDesmarcacaoPermitida);
$("[name=desmarcacao_permitida]").trigger('change');
</script>

@endpush