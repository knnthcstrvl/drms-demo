@php
    use Illuminate\Support\Str;
@endphp

<div class="flex items-center space-x-2">
    <img src="{{ Storage::disk('public')->exists('avatars/' . $row->username . '.jpg') 
        ? asset('storage/avatars/' . $row->username . '.jpg') 
        : asset('storage/avatars/default-user.jpg') }}"
        alt="Avatar of {{ $row->full_name }}"
        class="w-8 h-8 rounded-full">
    <span>{{ Str::title($row->full_name) }}</span>
</div>