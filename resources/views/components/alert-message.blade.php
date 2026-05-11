{{-- Use Props to pass data to the component --}}
@props(['type' => 'success', 'message', 'onDismiss'])

@php
    $styles = [
        'success' => 'bg-green-50 border-green-300 text-green-800',
        'error'   => 'bg-red-50 border-red-300 text-red-800',
    ];
@endphp

<div class="flex items-center gap-3 border px-4 py-3 rounded-lg {{ $styles[$type] }}">
    <span class="text-sm font-medium">{{ $message }}</span>
    <button wire:click="{{ $onDismiss }}" class="ml-auto opacity-60 hover:opacity-100">✕</button>
</div>
