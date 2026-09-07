@props([
    'tone' => 'warning',
    'role' => 'status',
])

<div {{ $attributes->class(['teelle-surface', 'teelle-state-message', 'teelle-state-message--'.$tone]) }} role="{{ $role }}">
    {{ $slot }}
</div>
