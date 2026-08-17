<?php
$operacoes=['+'=>'Add +','-'=>'Retirar -']
?>

<input type="hidden" name="student_id" value="{{$student->id}}">
<div class="row">
    <div class="col-sm-6 ">

        <x-form.number name="qtd" label="Qtd *" min="1" />

    </div>

    <div class="col-sm-6">
        <x-form.select name="operacao" :list="$operacoes" label="Operação *" />
    </div>

    <div class="col-sm-12">
        <x-form.text name="obs" label="Observação *" />
    </div>


</div>
