<x-app-layout>
    <div class="px-6 lg:px-8 py-6 max-w-6xl">

        <div class="mb-6">
            <h1 class="text-lg font-semibold text-gray-900 tracking-tight">Interviews</h1>
            <p class="text-sm text-gray-500 mt-0.5">All screening, assessment, and interview stages across your applications.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            @foreach ([['Upcoming', $upcoming->count(), 'text-gray-900'], ['Scheduled past', $past->count(), 'text-gray-500'], ['Unscheduled', $unscheduled->count(), 'text-gray-500']] as $stat)
                <div class="border border-gray-200 rounded-lg bg-white px-5 py-4">
                    <div class="text-xs text-gray-500">{{ $stat[0] }}</div>
                    <div class="text-2xl font-semibold mt-1 {{ $stat[2] }}">{{ $stat[1] }}</div>
                </div>
            @endforeach
        </div>

        @php
            $dot = fn($i) => match(true) {
                $i->schedule_date === null => 'bg-gray-300',
                $i->schedule_date->isFuture() => 'bg-emerald-500',
                $i->schedule_date->isToday() => 'bg-emerald-500',
                default => 'bg-gray-300',
            };
        @endphp

        @forelse ($upcoming->merge($unscheduled) as $interview)
            <div class="border border-gray-200 rounded-lg bg-white mb-3 px-5 py-4 flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="flex items-center gap-2.5 min-w-0">
                    <span class="h-2 w-2 rounded-full {{ $dot($interview) }} shrink-0"></span>
                    <div class="min-w-0">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $interview->interview_stage }}
                            <span class="text-gray-400 font-normal">·</span>
                            <span class="text-gray-600">{{ $interview->application?->company ?? '—' }}</span>
                        </div>
                        @if ($interview->interviewer)
                            <div class="text-xs text-gray-500 mt-0.5">{{ $interview->interviewer }}</div>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-4 sm:ml-auto text-xs text-gray-500">
                    @if ($interview->schedule_date)
                        <span>{{ $interview->schedule_date->format('d MMM Y · HH:mm') }}</span>
                    @endif
                    @if ($interview->link_meeting)
                        <a href="{{ $interview->link_meeting }}" target="_blank" rel="noopener" class="text-gray-900 hover:underline underline decoration-gray-300 underline-offset-2">Join</a>
                    @endif
                    <a href="{{ route('applications.show', $interview->application_id) }}" class="text-gray-500 hover:text-gray-900 transition">Details</a>
                </div>
            </div>
        @empty
            <div class="border border-gray-200 rounded-lg bg-white py-16 text-center">
                <p class="text-sm text-gray-500">No interviews recorded yet.</p>
                <a href="{{ route('applications.index') }}" class="inline-block mt-3 text-sm text-gray-900 font-medium hover:underline">Go to applications</a>
            </div>
        @endforelse

        @if ($past->count())
            <div class="mt-8 mb-3 text-xs font-medium text-gray-400 uppercase tracking-wide">History</div>
            @foreach ($past as $interview)
                <div class="border border-gray-200 rounded-lg bg-white mb-2 px-5 py-3.5 flex flex-col sm:flex-row sm:items-center gap-2 opacity-70">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <span class="h-2 w-2 rounded-full bg-gray-300 shrink-0"></span>
                        <span class="text-sm text-gray-600 truncate">{{ $interview->interview_stage }} · {{ $interview->application?->company }}</span>
                    </div>
                    <span class="text-xs text-gray-400 sm:ml-auto">{{ $interview->schedule_date?->format('d MMM Y') }}</span>
                </div>
            @endforeach
        @endif
    </div>
</x-app-layout>
