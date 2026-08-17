@props([
    'name',
    'value' => null,
    'label' => null,
])

@php
    $current = old($name, $value);
    $hasError = $errors->has($name);
    $labelText = $label;
@endphp

@if ($labelText)
    <label class="control-label" for="{{ $name }}">{{ $labelText }}</label>
@endif
<textarea
    name="{{ $name }}"
    id="{{ $name }}"
    {{ $attributes->merge(['class' => 'form-control']) }}
>{{ $current }}</textarea>
@if ($hasError)
    <div class="invalid-feedback">{{ $errors->first($name) }}</div>
@endif
