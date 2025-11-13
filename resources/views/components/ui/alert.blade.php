@props(['type' => 'info', 'message'])

@php
    $classes = match($type) {
        'success' => 'bg-green-50 border-green-500 text-green-800',
        'error' => 'bg-red-50 border-red-500 text-red-800',
        'warning' => 'bg-yellow-50 border-yellow-500 text-yellow-800',
        'info' => 'bg-blue-50 border-blue-500 text-blue-800',
        default => 'bg-gray-50 border-gray-500 text-gray-800',
    } . ' border-l-4 p-4 rounded-lg shadow-sm';
@endphp

<div x-data="{ show: true }" x-show="show" x-transition class="{{ $classes }}">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            @if($type === 'success')
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            @elseif($type === 'error')
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h-1m-6 0a3 3 0 1112 0m-12 0h1m11 0h1m-12 0v-4m12 4v-4m-12 0a3 3 0 1112 0m-12 0v-4m12 4v-4m-12 0a3 3 0 1112 0m-12 0v-4m12 4v-4m-12 0a3 3 0 1112 0m-12 0v-4m12 4v-4m-12 0a3 3 0 1112 0m-12 0v-4m12 4v-4m-12 0a3 3 0 1112 0m-12 0v-4m12 4v-4m-12 0a3 3 0 1112 0m-12 0v-4m12 4v-4m-12 0a3 3 0 1112 0m-12 0v-4m12 4v-4m-12 0a3 3 0 1112 0m-12 0v-4m12 4v-4m-12 0a3 3 0 1112 0m-12 0v-4m12 4v-4m-12 0a3 3 0 1112 0m-12 0v-4m12 4v-4m-12 0a3 3 0 1112 0m-12 0v-4m12 4v-4m-12 0a3 3 0 1112 0m-12 0v-4m12 4v-4m-12 0a3 3 0 1112 0m-12 0v-4m12 4v-4m-12 0a3 3 0 1112 0m-12 0v......"></path>
                </svg>
            @endif
            <p class="text-sm font-medium">{{ $message }}</p>
        </div>
        <button @click="show = false" class="text-gray-500 hover:text-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>