@props([
    'as' => 'section',
    'tone' => 'default',
])

<{{ $as }} {{ $attributes->class(['teelle-surface', 'teelle-surface--warm' => $tone === 'warm']) }}>
    {{ $slot }}
</{{ $as }}>
