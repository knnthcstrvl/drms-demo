@php
    use Illuminate\Support\Str;
@endphp

<div class="flex items-center space-x-2">
    <x-app.initials-avatar :name="$row->full_name" class="w-8 h-8 text-xs shrink-0" />
    <span>{{ Str::title($row->full_name) }}</span>
</div>
