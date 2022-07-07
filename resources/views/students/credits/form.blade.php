<?php
$operacoes=['+'=>'Add +','-'=>'Retirar -']
?>

<input type="hidden" name="student_id" value="{{$student->id}}">
<div class="row">
    <div class="col-sm-6 ">

        {{ Form::bsNumber('qtd',null,['label'=>"Qtd *",'min'=>'1']) }}

    </div>

    <div class="col-sm-6">
        {{ Form::bsSelect('operacao',$operacoes,null,['label'=>"Operação *"]) }}
    </div>

    <div class="col-sm-12">
        {{ Form::bsText('obs',null,['label'=>"Observação *"]) }}
    </div>


</div>