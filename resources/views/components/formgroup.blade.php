{{-- resources/views/components/formgroup.blade.php --}}

<?php
$doc = new DOMDocument();
$doc->loadHTML($slot);
$in = $doc->getElementsByTagName('input')[0];
$id = '';
$name = '';

//se não for input, tentar com select
if(!$in):
    $in = $doc->getElementsByTagName('select')[0];
endif;

if ($in):
    $id = $in->getAttribute('id');
    $name = $in->getAttribute('name');
endif;

$label=isset($label)?$label:ucfirst($name);
$class_erro = $errors->has($name) ? 'has-danger' : '';
?>

<div class="form-group {{$class_erro}}">
    <label class="form-control-label" for="{{$id}}">{{$label}}</label>
    {{$slot}}
   @if($class_erro)
   <div class='invalid-feedback'> {{$errors->first($name)}}</div>
   @endif
</div>
