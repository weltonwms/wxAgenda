@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Configurações Globais</div>

                <div class="card-body">
                    <div class="form-row">
                    {!! Form::open(['route'=>'configurations.save','class'=>'','id'=>'ConfigurationForm'])!!}
                        <div class="col-md-12">
                            <label for="" class="control-label yesno">Agendamento Ativo</label>
                            {{ Form::bsYesno('agendamento_ativo',config('agenda.agendamento_ativo')) }}
                        </div>
                        <div class="col-md-12 mt-3">

                            {{ Form::bsNumber('celula_limit',config('agenda.celula_limit'),['label'=>"Limite de uma Célula de Aula",'min'=>'1']) }}
                        </div>

                        <div class="col-md-12 mt-3">

                            {{ Form::bsNumber('desmarcacao_limit_by_month',config('agenda.desmarcacao_limit_by_month'),
                            ['label'=>"Limite de Desmarcação de Aulas por Mês",'min'=>'0']) }}
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