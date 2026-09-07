@props([
    'label',
    'name',
    'type' => 'text',
    'hint' => null,
    'error' => null,
])

@php
    $inputId = $attributes->get('id', $name);
    $hintId = $hint ? $inputId.'-hint' : null;
    $errorId = $error ? $inputId.'-error' : null;
    $describedBy = collect([$hintId, $errorId])->filter()->implode(' ');
@endphp

<div class="teelle-field">
    <label class="teelle-field__label" for="{{ $inputId }}">{{ $label }}</label>
    @if ($hint)
        <p class="teelle-field__hint" id="{{ $hintId }}">{{ $hint }}</p>
    @endif
    <input
        id="{{ $inputId }}"
        name="{{ $name }}"
        type="{{ $type }}"
        @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
        @if ($error) aria-invalid="true" @endif
        {{ $attributes->except('id')->class('teelle-input') }}
    >
    @if ($error)
        <p class="teelle-field__error" id="{{ $errorId }}">{{ $error }}</p>
    @endif
</div>
