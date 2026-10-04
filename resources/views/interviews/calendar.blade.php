<x-app-layout>
    <div class="px-6 lg:px-8 py-6 max-w-6xl">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <div>
                <h1 class="text-lg font-semibold text-gray-900 tracking-tight">Interview calendar</h1>
                <p class="text-sm text-gray-500 mt-0.5">All scheduled interview stages on one grid.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('interviews.index') }}" class="text-xs font-medium text-gray-500 hover:text-gray-900 transition px-2 py-1.5 border border-gray-300 rounded-md hover:bg-gray-50">List view</a>
                <a href="{{ route('interviews.calendar', ['year' => $first->copy()->subMonthNoOverflow()->year, 'month' => $first->copy()->subMonthNoOverflow()->month]) }}"
                   class="text-xs font-medium text-gray-700 px-2 py-1.5 border border-gray-300 rounded-md hover:bg-gray-50 transition">← Prev</a>
                <a href="{{ route('interviews.calendar') }}" class="text-xs font-medium text-gray-700 px-2.5 py-1.5 border border-gray-300 rounded-md hover:bg-gray-50 transition">Today</a>
                <a href="{{ route('interviews.calendar', ['year' => $last->copy()->addMonthNoOverflow()->year, 'month' => $last->copy()->addMonthNoOverflow()->month]) }}"
                   class="text-xs font-medium text-gray-700 px-2 py-1.5 border border-gray-300 rounded-md hover:bg-gray-50 transition">Next →</a>
            </div>
        </div>

        @php
            $weekdays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            $gridStart = $first->copy()->startOfWeek(Carbon\Carbon::MONDAY)->copy();
            $gridEnd = $last->copy()->endOfWeek(Carbon\Carbon::SUNDAY)->copy();
            $cells = [];
            for ($d = $gridStart; $d <= $gridEnd; $d = $d->copy()->addDay()) {
                $cells[] = $d;
            }
        @endphp

        <div class="border border-gray-200 rounded-lg bg-white overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-sm font-medium text-gray-900">{{ $first->format('F Y') }}</h2>
                <span class="text-xs text-gray-400">{{ $interviews->flatten()->count() }} interview(s) this month</span>
            </div>

            <div class="grid grid-cols-7 border-b border-gray-200 text-center text-[11px] font-medium text-gray-500 uppercase tracking-wide">
                @foreach ($weekdays as $wd)
                    <div class="py-2 border-l first:border-l-0 border-gray-200">{{ $wd }}</div>
                @endforeach
            </div>

            <div class="grid grid-cols-7">
                @foreach ($cells as $day)
                    @php
                        $key = $day->toDateString();
                        $dayItems = $interviews->get($key, collect());
                        $inMonth = $day->month === $first->month;
                    @endphp
                    <div class="min-h-[76px] p-1.5 border-t border-l first:border-l-0 border-gray-200 {{ $inMonth ? 'bg-white' : 'bg-gray-50' }}">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-medium tabular-nums {{ $day->isToday() ? 'text-indigo-600' : ($inMonth ? 'text-gray-600' : 'text-gray-300') }}">{{ $day->day }}</span>
                            @if ($day->isToday())
                                <span class="text-[10px] font-semibold text-indigo-600 bg-indigo-50 rounded px-1.5 py-0.5">Today</span>
                            @endif
                        </div>
                        @foreach ($dayItems->take(3) as $item)
                            <a href="{{ route('applications.show', $item->application_id) }}"
                               class="block text-[11px] font-medium bg-gray-100 hover:bg-gray-200 rounded px-1.5 py-1 mb-1 transition-colors">
                                <span class="block truncate">{{ $item->interview_stage }} · {{ $item->application?->company }}</span>
                                <span class="text-gray-400 tabular-nums">{{ $item->schedule_date->format('H:i') }}</span>
                            </a>
                        @endforeach
                        @if ($dayItems->count() > 3)
                            <span class="text-[11px] text-gray-400 px-1.5">+{{ $dayItems->count() - 3 }} more</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
