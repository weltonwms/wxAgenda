<h5 class=" text-center">Dados Pessoais</h5>

{{ Form::model(auth()->user()->student, ['url' => 'perfil/saveDadosStudent']) }}

<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="row">
            <div class="col-md-12">
                {{ Form::bsText('nome',null,['label'=>"Nome *",'class'=>""]) }}
            </div>
            <div class="col-md-6">
                {{ Form::bsText('cpf',null,['label'=>"CPF ",'oninput'=>"this.value = this.value.replace(/[^0-9]/g, '')"]) }}
            </div>
            <div class="col-md-6">
                {{ Form::bsText('telefone',null,['label'=>"Telefone *",'class'=>""]) }}
            </div>
        </div>
    </div>
</div>
<div class="text-center">
    <button type="submit" class="btn btn-primary">
        <i class="fa fa-fw fa-lg fa-check-circle"></i>
        Salvar Dados
    </button>
</div>

{!! Form::close() !!}