@props(['title', 'description' => null, 'action' => null, 'actionLabel' => null])

<div class="py-16 px-6 text-center">
    <div class="mx-auto h-10 w-10 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center mb-4">
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.19-5.19M17.25 11.25a5.995 5.995 0 11-11.99 0 5.995 5.995 0 0111.99 0z"/>
        </svg>
    </div>
    <h3 class="text-sm font-medium text-gray-900">{{ $title }}</h3>
    @if (filled($description))
        <p class="text-sm text-gray-500 mt-1">{{ $description }}</p>
    @endif
    @if ($action)
        <a href="{{ $action }}" class="inline-flex items-center gap-1.5 mt-4 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium px-3.5 py-2 rounded-md transition-colors">
            {{ $actionLabel ?? 'Get started' }}
        </a>
    @endif
</div>
