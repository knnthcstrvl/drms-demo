@props([
    'name' => '',
])

@php
    use Illuminate\Support\Str;

    $parts = collect(preg_split('/\s+/', trim((string) $name)))->filter()->values();

    $initials = match ($parts->count()) {
        0 => '',
        1 => Str::upper(Str::substr((string) $parts->first(), 0, 1)),
        default => Str::upper(Str::substr((string) $parts->first(), 0, 1))
            . Str::upper(Str::substr((string) $parts->last(), 0, 1)),
    };

    $initials = $initials !== '' ? $initials : 'U';

    $palettes = [
        ['bg-blue-100', 'text-blue-700'],
        ['bg-emerald-100', 'text-emerald-700'],
        ['bg-amber-100', 'text-amber-700'],
        ['bg-rose-100', 'text-rose-700'],
        ['bg-indigo-100', 'text-indigo-700'],
        ['bg-cyan-100', 'text-cyan-700'],
        ['bg-lime-100', 'text-lime-700'],
        ['bg-orange-100', 'text-orange-700'],
    ];

    $index = abs(crc32(Str::lower((string) $name))) % count($palettes);
    [$bgClass, $textClass] = $palettes[$index];
@endphp

<div {{ $attributes->merge(['class' => "inline-flex items-center justify-center rounded-full font-semibold uppercase select-none {$bgClass} {$textClass}"]) }}>
    <span class="leading-none">{{ $initials }}</span>
</div>
