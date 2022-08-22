
<?php

$estadosBrasileiros = array(
    'AC'=>'AC',
    'AL'=>'AL',
    'AP'=>'AP',
    'AM'=>'AM',
    'BA'=>'BA',
    'CE'=>'CE',
    'DF'=>'DF',
    'ES'=>'ES',
    'GO'=>'GO',
    'MA'=>'MA',
    'MT'=>'MT',
    'MS'=>'MS',
    'MG'=>'MG',
    'PA'=>'PA',
    'PB'=>'PB',
    'PR'=>'PR',
    'PE'=>'PE',
    'PI'=>'PI',
    'RJ'=>'RJ',
    'RN'=>'RN',
    'RS'=>'RS',
    'RO'=>'RO',
    'RR'=>'RR',
    'SC'=>'SC',
    'SP'=>'SP',
    'SE'=>'SE',
    'TO'=>'TO',
    'EX'=>'Exterior',
    );

?>


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

    <div class="col md-4">
        {{ Form::bsSelect('module_id',$modulesList,null,['label'=>"Módulo Corrente", 'placeholder' => '--Selecione--','class'=>'select2']) }}
    </div>
    <div class="col-md-2">
    {{ Form::bsNumber('horas_contratadas')}}
    </div>

    <div class="col-md-6">
        {{ Form::bsText('endereco',null,['label'=>"Endereço",'class'=>""]) }}

    </div>
    <div class="col-md-4">
        {{ Form::bsText('cidade',null,['label'=>"Cidade",'class'=>""]) }}

    </div>
    <div class="col-md-2">
        {{ Form::bsSelect('uf',$estadosBrasileiros,null,['label'=>"UF",'placeholder' => '--Selecione--','class'=>'select2']) }}

    </div>


</div>