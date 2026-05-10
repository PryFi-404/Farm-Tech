@props([
    'title',
    'value',
    'icon',
    'color'  => 'green',   // green | blue | orange | purple | red
    'change' => null,
    'link'   => null,
])

@php
$colors = [
    'green'  => ['bg' => 'bg-green-50',  'icon' => 'bg-green-500',  'text' => 'text-green-600',  'border' => 'border-l-green-500'],
    'blue'   => ['bg' => 'bg-blue-50',   'icon' => 'bg-blue-500',   'text' => 'text-blue-600',   'border' => 'border-l-blue-500'],
    'orange' => ['bg' => 'bg-orange-50', 'icon' => 'bg-orange-500', 'text' => 'text-orange-600', 'border' => 'border-l-orange-500'],
    'purple' => ['bg' => 'bg-purple-50', 'icon' => 'bg-purple-500', 'text' => 'text-purple-600', 'border' => 'border-l-purple-500'],
    'red'    => ['bg' => 'bg-red-50',    'icon' => 'bg-red-500',    'text' => 'text-red-600',    'border' => 'border-l-red-500'],
];
$c = $colors[$color] ?? $colors['green'];
@endphp

<div class="stat-card border-l-4 {{ $c['border'] }} group">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ $title }}</p>
            <p class="mt-1 text-3xl font-bold text-gray-800">{{ $value }}</p>
            @if($change)
            <p class="mt-1 text-xs {{ $c['text'] }} font-medium">{{ $change }}</p>
            @endif
        </div>
        <div class="{{ $c['icon'] }} w-12 h-12 rounded-xl flex items-center justify-center
                    shadow-sm group-hover:scale-110 transition-transform duration-200">
            <span class="text-white">{!! $icon !!}</span>
        </div>
    </div>
    @if($link)
    <a href="{{ $link }}" class="mt-3 flex items-center gap-1 text-xs {{ $c['text'] }} hover:underline font-medium">
        View all
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
    @endif
</div>
