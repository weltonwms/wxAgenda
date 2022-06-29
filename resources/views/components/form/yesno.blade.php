{{-- resources/views/components/form/yesno.blade.php --}}
<?php


$vlModel=Form::getValueAttribute($name);
if(!is_numeric($vlModel)){
    $vlModel=$default;
}


$activeYes=$vlModel=="1"?'active':'';
$activeNo=$vlModel=="0"?'active':'';

$valueYes=$vlModel=="1"?true:null;
$valueNo=$vlModel=="0"?true:null;

?>
<div class="btn-group groupyesno btn-group-toggle" data-toggle="buttons">
    
    <label class="btn yes btn-outline-secondary {{$activeYes}}">
        {{Form::radio($name, '1', $valueYes)}}Sim
      
    </label>
    <label class="btn no btn-outline-secondary  {{$activeNo}} ">
        {{Form::radio($name, '0', $valueNo)}}
         Não
    </label>
  </div>