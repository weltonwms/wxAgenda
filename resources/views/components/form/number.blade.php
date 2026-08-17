@props([
    'name',
    'value' => null,
    'label' => null,
])

@php
    $current = old($name, $value);
    $hasError = $errors->has($name);
    $labelText = $label ?: str_replace('_', ' ', ucfirst($name));
@endphp

<div class="form-group {{ $hasError ? 'has-danger' : '' }}">
    <label class="control-label" for="{{ $name }}">{{ $labelText }}</label>
    <input
        type="number"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $current }}"
        {{ $attributes->merge(['class' => 'form-control']) }}
    >
    @if ($hasError)
        <div class="invalid-feedback">{{ $errors->first($name) }}</div>
    @endif
</div>
