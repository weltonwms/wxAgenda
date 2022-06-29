<div class="form-row">
    <div class="col-md-12">
        {{ Form::bsSelect('module_id',$modulesList,null,['label'=>"Módulo *", 'placeholder' => '--Selecione--','class'=>'select2']) }}
    </div>
    <div class="col-md-12">
        {{ Form::bsSelect('disciplina_id',$disciplinasList,null,['label'=>"Disciplina *", 'placeholder' => '--Selecione--','class'=>'select2']) }}
    </div>

    <div class="col-md-6">
    {{ Form::bsText('sigla', null,['label'=>"Sigla *"]) }}
    </div>

    <div class="col-md-6">
        {{ Form::bsNumber('ordem',null,['label'=>"Ordem *",'min'=>'1']) }}
    </div>


</div>