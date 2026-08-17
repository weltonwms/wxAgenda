@props([
    'name',
    'value' => null,
    'default' => null,
])

@php
    $current = old($name, $value);
    if ($current === null || $current === '') {
        $current = $default;
    }
    $isYes = (string) $current === '1';
    $isNo = (string) $current === '0';
@endphp

<div {{ $attributes->class(['btn-group', 'groupyesno', 'btn-group-toggle']) }} data-toggle="buttons">
    <label class="btn yes btn-outline-secondary {{ $isYes ? 'active' : '' }}">
        <input type="radio" name="{{ $name }}" value="1" @checked($isYes)> Sim
    </label>
    <label class="btn no btn-outline-secondary {{ $isNo ? 'active' : '' }}">
        <input type="radio" name="{{ $name }}" value="0" @checked($isNo)> Não
    </label>
</div>
