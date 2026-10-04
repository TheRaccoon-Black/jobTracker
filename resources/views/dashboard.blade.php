<x-app-layout>
    <div class="px-6 lg:px-8 py-6 max-w-6xl">

        <div class="mb-6 flex items-end justify-between gap-4">
            <div>
                <h1 class="text-lg font-semibold text-gray-900 tracking-tight">Dashboard</h1>
                <p class="text-sm text-gray-500 mt-0.5">Your job search at a glance.</p>
            </div>
            <a href="{{ route('applications.board') }}"
               class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-700 border border-gray-300 rounded-md px-3 py-1.5 hover:bg-gray-50 transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                Pipeline view
            </a>
        </div>

        @if (session('success'))
            <div class="mb-5 border border-emerald-200 bg-emerald-50 rounded-md px-4 py-2.5 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Stat row --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-px bg-gray-200 border border-gray-200 rounded-lg overflow-hidden mb-6">
            @foreach ([
                ['Total applications', $totalApplications],
                ['In progress', $activeCount],
                ['Offers', $offerCount],
                ['Conversion rate', $conversionRate.'%'],
            ] as $stat)
                <div class="bg-white px-5 py-4">
                    <div class="text-xs text-gray-500">{{ $stat[0] }}</div>
                    <div class="text-2xl font-semibold text-gray-900 mt-1 tabular-nums tracking-tight">{{ $stat[1] }}</div>
                </div>
            @endforeach
        </div>

        {{-- Weekly target progress --}}
        @php
            $weekPct = $weeklyTarget > 0 ? min(100, round(($thisWeek / $weeklyTarget) * 100)) : 0;
            $weekHit = $thisWeek >= $weeklyTarget;
        @endphp
        <div class="flex items-center gap-4 border {{ $weekHit ? 'border-emerald-200 bg-emerald-50' : 'border-gray-200 bg-white' }} rounded-lg px-5 py-3.5 mb-6">
            <div class="relative h-12 w-12 shrink-0" title="{{ $thisWeek }} / {{ $weeklyTarget }} this week">
                <svg class="h-12 w-12 -rotate-90" viewBox="0 0 36 36">
                    <circle cx="18" cy="18" r="15.5" fill="none" stroke="#E5E7EB" stroke-width="3.5"/>
                    <circle cx="18" cy="18" r="15.5" fill="none"
                            stroke="{{ $weekHit ? '#10B981' : '#4B5563' }}" stroke-width="3.5"
                            stroke-linecap="round"
                            stroke-dasharray="{{ round($weekPct / 100 * 97.4, 1) }} 97.4"/>
                </svg>
                <span class="absolute inset-0 flex items-center justify-center text-xs font-semibold tabular-nums {{ $weekHit ? 'text-emerald-700' : 'text-gray-700' }}">{{ $weekPct }}%</span>
            </div>
            <div class="min-w-0">
                <div class="text-sm font-medium text-gray-900">
                    This week: <span class="tabular-nums">{{ $thisWeek }}</span> / {{ $weeklyTarget }} applications
                    @if ($weekHit)
                        <span class="ml-1.5 text-xs font-medium text-emerald-700">Target hit ✓</span>
                    @endif
                </div>
                <div class="text-xs text-gray-500 mt-0.5">
                    @if ($weekHit)
                        Great pace — keep it up.
                    @else
                        {{ $weeklyTarget - $thisWeek }} more to hit your weekly target (set it in <a href="{{ route('profile.edit') }}" class="underline hover:no-underline">Profile</a>).
                    @endif
                </div>
            </div>
        </div>

        {{-- Funnel + breakdown --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">
            {{-- Funnel --}}
            <div class="border border-gray-200 rounded-lg bg-white">
                <div class="px-5 py-3.5 border-b border-gray-200">
                    <h2 class="text-sm font-medium text-gray-900">Pipeline funnel</h2>
                </div>
                <div class="p-5 space-y-3">
                    @foreach ($funnelStages as $stage)
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="text-gray-600">{{ $stage['label'] }}</span>
                                <span class="text-gray-400 tabular-nums">{{ $stage['count'] }} · {{ $stage['pct'] }}%</span>
                            </div>
                            <div class="h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full rounded-full bg-gray-900" style="width: {{ $stage['pct'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Status distribution (donut) --}}
            <div class="border border-gray-200 rounded-lg bg-white">
                <div class="px-5 py-3.5 border-b border-gray-200">
                    <h2 class="text-sm font-medium text-gray-900">Status breakdown</h2>
                </div>
                <div class="p-5">
                    @if ($statusDistribution->count() === 0)
                        <p class="text-sm text-gray-500">No applications yet.</p>
                    @else
                        @php
                            $total = $statusDistribution->sum();
                            $palette = [
                                'Wishlist'       => '#9ca3af',
                                'Applied'        => '#3b82f6',
                                'Assessment'     => '#f59e0b',
                                'HR Interview'   => '#10b981',
                                'User Interview' => '#8b5cf6',
                                'Offered'        => '#16a34a',
                                'Rejected'       => '#dc2626',
                                'Ghosted'        => '#d1d5db',
                            ];
                            $offset = 0;
                            $segments = [];
                            foreach ($statusDistribution as $status => $count) {
                                $pct = ($count / $total) * 100;
                                $segments[] = ['status' => $status, 'count' => $count, 'pct' => $pct,
                                                'color' => $palette[$status] ?? '#9ca3af', 'offset' => $offset];
                                $offset += $pct;
                            }
                            $background = implode(',', array_map(fn($s) => "{$s['color']} {$s['offset']}% " . round($s['offset'] + $s['pct'], 1) . "%", $segments));
                        @endphp

                        <div class="flex items-center gap-6">
                            <div class="relative h-36 w-36 shrink-0">
                                <div class="h-full w-full rounded-full" style="background: conic-gradient({{ $background }});">
                                    <div class="absolute inset-[22px] bg-white rounded-full flex flex-col items-center justify-center">
                                        <span class="text-xl font-semibold text-gray-900 tabular-nums">{{ $total }}</span>
                                        <span class="text-[11px] text-gray-500">total</span>
                                    </div>
                                </div>
                            </div>
                            <ul class="flex-1 space-y-2 min-w-0">
                                @foreach ($segments as $seg)
                                    <li class="flex items-center gap-2 text-xs">
                                        <span class="h-2 w-2 rounded-full shrink-0" style="background: {{ $seg['color'] }};"></span>
                                        <span class="text-gray-600 truncate flex-1">{{ $seg['status'] }}</span>
                                        <span class="text-gray-900 font-medium tabular-nums">{{ $seg['count'] }}</span>
                                        <span class="text-gray-400 tabular-nums w-8 text-right">{{ round($seg['pct']) }}%</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Stale / follow-ups --}}
            <div class="space-y-5">
                <div class="border border-gray-200 rounded-lg bg-white">
                    <div class="px-5 py-3.5 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="text-sm font-medium text-gray-900">Stalled applications</h2>
                        @if ($staleApplications->count())
                            <span class="text-xs text-gray-400">{{ $staleApplications->count() }}</span>
                        @endif
                    </div>
                    @if ($staleApplications->count() === 0)
                        <p class="px-5 py-6 text-sm text-gray-400 text-center">Nothing is stalled — everything's moving.</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($staleApplications as $stale)
                                <li>
                                    <a href="{{ route('applications.show', $stale) }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition-colors">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500 shrink-0"></span>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900 truncate">{{ $stale->company }}</div>
                                            <div class="text-xs text-gray-500 truncate">{{ $stale->title }} · {{ $stale->status }}</div>
                                        </div>
                                        <span class="text-xs text-gray-400 tabular-nums shrink-0">{{ $stale->application_date?->diffForHumans(null, ['beforeInWords' => 0, 'afterInWords' => 0]) }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="border border-gray-200 rounded-lg bg-white">
                    <div class="px-5 py-3.5 border-b border-gray-200">
                        <h2 class="text-sm font-medium text-gray-900">Follow-ups due</h2>
                    </div>
                    @if ($dueFollowUps->count() === 0)
                        <p class="px-5 py-6 text-sm text-gray-400 text-center">No follow-ups due.</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($dueFollowUps as $fu)
                                <li class="px-5 py-3">
                                    <div class="flex items-center justify-between gap-2">
                                        <a href="{{ route('applications.show', $fu) }}" class="text-sm font-medium text-gray-900 hover:text-gray-600 transition-colors truncate">{{ $fu->company }}</a>
                                        <span class="text-xs tabular-nums shrink-0 {{ $fu->next_follow_up->isPast() ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                            {{ $fu->next_follow_up->isToday() ? 'Today' : ($fu->next_follow_up->isPast() ? ($fu->next_follow_up->diffForHumans() . ' overdue') : $fu->next_follow_up->format('d M')) }}
                                        </span>
                                    </div>
                                    @if ($fu->follow_up_notes)
                                        <p class="text-xs text-gray-500 mt-1 truncate">{{ $fu->follow_up_notes }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        {{-- Upcoming interviews + recent --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">
            <div class="lg:col-span-2 border border-gray-200 rounded-lg bg-white">
                <div class="px-5 py-3.5 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-sm font-medium text-gray-900">Upcoming interviews</h2>
                    <a href="{{ route('interviews.index') }}" class="text-xs text-gray-500 hover:text-gray-900 transition">All →</a>
                </div>
                @if ($upcomingInterviews->count() === 0)
                    <p class="px-5 py-8 text-sm text-gray-400 text-center">No upcoming interviews.</p>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach ($upcomingInterviews as $interview)
                            <li class="px-5 py-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="h-2 w-2 rounded-full {{ $interview->schedule_date->isToday() ? 'bg-emerald-500' : 'bg-gray-300' }} shrink-0"></span>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-medium text-gray-900 truncate">
                                            {{ $interview->interview_stage }}
                                            <span class="text-gray-400">·</span>
                                            <span class="text-gray-600">{{ $interview->application?->company }}</span>
                                        </div>
                                        @if ($interview->interviewer)
                                            <div class="text-xs text-gray-500 truncate mt-0.5">{{ $interview->interviewer }}</div>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-400 tabular-nums shrink-0">{{ $interview->schedule_date->format('d M · H:i') }}</span>
                                </div>
                                @if ($interview->link_meeting)
                                    <a href="{{ $interview->link_meeting }}" target="_blank" rel="noopener"
                                       class="text-xs text-gray-500 hover:text-gray-900 transition mt-1 block">Join meeting</a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="lg:col-span-3 border border-gray-200 rounded-lg bg-white">
                <div class="px-5 py-3.5 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-sm font-medium text-gray-900">Recent applications</h2>
                    <a href="{{ route('applications.index') }}" class="text-xs text-gray-500 hover:text-gray-900 transition">View all →</a>
                </div>

                @if ($recentApplications->isEmpty())
                    <div class="py-12 text-center">
                        <p class="text-sm text-gray-500">No applications yet.</p>
                        <a href="{{ route('applications.create') }}" class="inline-block mt-3 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium px-3.5 py-2 rounded-md transition-colors">Add your first application</a>
                    </div>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach ($recentApplications as $app)
                            <li>
                                <a href="{{ route('applications.show', $app) }}" class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition-colors">
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-medium text-gray-900 truncate">{{ $app->company }}</div>
                                        <div class="text-xs text-gray-500 truncate mt-0.5">{{ $app->title }}</div>
                                    </div>
                                    <x-status-badge :status="$app->status" />
                                    <span class="text-xs text-gray-400 tabular-nums w-20 text-right shrink-0">{{ $app->application_date?->format('d M Y') ?? '—' }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
