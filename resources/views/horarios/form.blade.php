<div class="row">
    <div class="col-sm-3">
       {{ Form::bsTime('horario',null,['label'=>"Horario *",'class'=>""]) }}
     </div>

     <div class="col-sm-4">
        {{ Form::bsSelect('turno_id',$turnosList,null,['label'=>"Turno *", 'placeholder' => '--Selecione--','class'=>'select2']) }}
    </div>

    
</div>