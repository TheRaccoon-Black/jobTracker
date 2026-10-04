<x-app-layout>
    <div class="px-6 lg:px-8 py-6 max-w-[1400px]">

        <div class="mb-6 flex items-end justify-between gap-4">
            <div>
                <h1 class="text-lg font-semibold text-gray-900 tracking-tight">Pipeline</h1>
                <p class="text-sm text-gray-500 mt-0.5">Drag applications between stages. Changes save automatically.</p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('applications.index') }}"
                   class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-700 border border-gray-300 rounded-md px-3 py-1.5 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h10.5"/></svg>
                    Table view
                </a>
                <a href="{{ route('applications.create') }}"
                   class="inline-flex items-center gap-1.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium px-3 py-1.5 rounded-md transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    New
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-5 border border-emerald-200 bg-emerald-50 rounded-md px-4 py-2.5 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        <x-applications-board-data
            :statuses="$statuses"
            :applications="$applications"
            :token="csrf_token()" />
    </div>
</x-app-layout>
