@props([
    'name',
    'list' => [],
    'value' => null,
    'label' => null,
    'placeholder' => null,
])

@php
    $current = old($name, $value);
    $hasError = $errors->has($name);
    $labelText = $label ?: str_replace('_', ' ', ucfirst($name));
@endphp

<div class="form-group {{ $hasError ? 'has-danger' : '' }}">
    <label class="control-label" for="{{ $name }}">{{ $labelText }}</label>
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-control']) }}
    >
        @if ($placeholder !== null)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($list as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) $current === (string) $optionValue && $current !== null && $current !== '')>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
    @if ($hasError)
        <div class="invalid-feedback">{{ $errors->first($name) }}</div>
    @endif
</div>
