
<div class="row">
    <div class="col-md-6">
        {{ Form::bsText('nome',null,['label'=>"Nome *",'class'=>""]) }}


    </div>

    <div class="col-md-6">
        {{ Form::bsText('email',null,['label'=>"Email *",'class'=>""]) }}

    </div>

    <div class="col-md-6">
        {{ Form::bsText('telefone',null,['label'=>"Telefone *",'class'=>""]) }}

    </div>

    <div class="col md-6">
        {{ Form::bsSelect('module_id',$modulesList,null,['label'=>"Módulo Corrente", 'placeholder' => '--Selecione--','class'=>'select2']) }}
    </div>

    <div class="col-md-6">
        {{ Form::bsText('endereco',null,['label'=>"Endereço",'class'=>""]) }}

    </div>
    <div class="col-md-4">
        {{ Form::bsText('cidade',null,['label'=>"Cidade",'class'=>""]) }}

    </div>
    <div class="col-md-2">
        {{ Form::bsText('uf',null,['label'=>"UF",'class'=>""]) }}

    </div>


</div>