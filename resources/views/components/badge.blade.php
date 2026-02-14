@props([
    'text',
    'color'
])

<span class="badge bg-{{ $color }}">
    {{ $text }}
</span>
