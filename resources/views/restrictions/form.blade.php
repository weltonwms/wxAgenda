<div class="form-row">
    <div class="col-md-12">
        {{ Form::bsSelect('module_id',$modulesList,null,['label'=>"Módulo *", 'placeholder' => '--Selecione--','class'=>'select2']) }}
    </div>
    <div class="col-md-12">
        {{ Form::bsSelect('disciplina_id',$disciplinasList,null,['label'=>"Disciplina *", 'placeholder' => '--Selecione--','class'=>'select2']) }}
    </div>

    <div class="col-md-6">
        {{ Form::bsNumber('level_start',null,['label'=>"Nível Início*",'min'=>'1']) }}
    </div>

    <div class="col-md-6">
        {{ Form::bsNumber('level_end',null,['label'=>"Nível Fim*",'min'=>'2']) }}
    </div>


</div>