<div class="row">
    <div class="col-md-2">
       {{ Form::bsTime('horario',null,['label'=>"Horario *",'class'=>""]) }}
     </div>

     <div class="col-md-4">
        {{ Form::bsSelect('turno_id',$turnosList,null,['label'=>"Turno *", 'placeholder' => '--Selecione--','class'=>'select2']) }}
    </div>

    
</div>