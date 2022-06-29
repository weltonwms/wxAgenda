{{-- resources/views/components/form/date.blade.php --}}
<?php
//Nesse Componente não é necessário passar id ou label. O label repete o name com ucFirst, 
//porém se o label for diferente terá que passar como atributo

$class_erro = $errors->has($name) ? 'has-danger' : '';
$label=null; //valor padrão para o Form::label automatizar o for label e valor.
if (isset($attributes['label']) ):
    $label=$attributes['label'];
    unset($attributes['label']);
endif;
$classes='';
if (isset($attributes['class']) ):
    $classes=$attributes['class'];
    unset($attributes['class']);
endif;

?>
<div class="form-group {{$class_erro}} ">
    {{ Form::label($name, $label, ['class' => 'control-label']) }}
    {{ Form::date($name, $value, array_merge(['class' => "form-control $classes"], $attributes)) }}
    @if($class_erro)
    <div class='invalid-feedback'> {{$errors->first($name)}}</div>
    @endif
</div>