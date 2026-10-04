<x-app-layout>
    <div class="px-6 lg:px-8 py-6 max-w-6xl">

        <div class="flex items-end justify-between gap-4 mb-5">
            <div>
                <h1 class="text-lg font-semibold text-gray-900 tracking-tight">Applications</h1>
                <p class="text-sm text-gray-500 mt-0.5">Every application across all pipelines.</p>
            </div>
            <a href="{{ route('applications.board') }}"
               class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-700 border border-gray-300 rounded-md px-3 py-1.5 hover:bg-gray-50 transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25z"/></svg>
                Board view
            </a>
        </div>

        @if (session('success'))
            <div class="mb-5 border border-emerald-200 bg-emerald-50 rounded-md px-4 py-2.5 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Toolbar: filters + count --}}
        <div x-data="{
                checked: 0,
                statuses: @js($statuses),
                bulkStatus: '',
                bulkAction: '',
                busy: false,
                sync() { this.checked = document.querySelectorAll('input[name=\'ids[]\']:checked').length },
                selectAll(cb) { document.querySelectorAll('input[name=\'ids[]\']').forEach(c => c.checked = cb); this.sync() },
                async run() {
                    const ids = Array.from(document.querySelectorAll('input[name=\'ids[]\']:checked')).map(i => i.value)
                    if (!ids.length) return
                    this.busy = true
                    const fd = new FormData()
                    fd.append('_token', @js(csrf_token()))
                    fd.append('action', this.bulkAction)
                    if (this.bulkAction === 'set_status') fd.append('status', this.bulkStatus)
                    ids.forEach(id => fd.append('ids[]', id))
                    await fetch('{{ route('applications.bulk') }}', { method: 'POST', body: fd })
                    this.busy = false
                    window.location.reload()
                }
            }" x-init="sync()" class="border border-gray-200 rounded-lg bg-white">
            <div class="flex flex-col lg:flex-row lg:items-center gap-3 px-4 py-3 border-b border-gray-200">
                <form method="GET" action="{{ route('applications.index') }}" class="flex flex-1 flex-col sm:flex-row sm:items-center gap-3">
                    <div class="relative flex-1 sm:max-w-xs">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.25-5.25M17.25 13.5L21 17.25M15.75 6.75a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search company or role…"
                               class="w-full pl-9 pr-3 py-1.5 text-sm border border-gray-300 rounded-md focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400">
                    </div>
                    <select name="status" class="text-sm border border-gray-300 rounded-md px-3 py-1.5 bg-white focus:outline-none focus:border-gray-400">
                        <option value="">All statuses</option>
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" @selected($selectedStatus === $s)>{{ $s }}</option>
                        @endforeach
                    </select>
                    @if (!empty($allTags))
                        <select name="tags" class="text-sm border border-gray-300 rounded-md px-3 py-1.5 bg-white focus:outline-none focus:border-gray-400 max-w-[140px]">
                            <option value="">All tags</option>
                            @foreach ($allTags as $t)
                                <option value="{{ $t }}" @selected($selectedTags === $t)>#{{ $t }}</option>
                            @endforeach
                        </select>
                    @endif
                    <button type="submit" class="text-sm font-medium text-gray-700 px-3 py-1.5 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">Filter</button>
                    <a href="{{ route('applications.index') }}" class="text-sm text-gray-500 hover:text-gray-900 transition px-1">Reset</a>
                    <a href="{{ route('applications.export') }}{{ $search ? ('?search='.urlencode($search)) : '' }}{{ $selectedStatus ? ((($search ? '&' : '?').'status='.urlencode($selectedStatus))) : '' }}"
                       class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-700 px-3 py-1.5 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors ml-auto">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                        Export CSV
                    </a>
                </form>
            </div>

            {{-- Bulk actions bar (visible when rows are selected) --}}
            <div x-show="checked > 0" x-cloak class="flex flex-wrap items-center gap-3 px-4 py-2.5 bg-gray-900 text-white border-b border-gray-200">
                    <span class="text-sm font-medium tabular-nums" x-text="checked + ' selected'"></span>
                    <template x-if="bulkAction === 'set_status'">
                        <select x-model="bulkStatus" class="text-sm bg-gray-800 border border-gray-700 rounded-md px-2.5 py-1.5 text-gray-100 focus:outline-none">
                            <option value="" disabled>Select new status…</option>
                            <option x-for="s in statuses" :value="s" x-text="s"></option>
                        </select>
                    </template>
                    <button type="button" @click="bulkAction = 'set_status'"
                            class="text-sm font-medium px-3 py-1.5 rounded-md bg-emerald-600 hover:bg-emerald-500 transition-colors">
                        Set status
                    </button>
                    <button type="button" @click="if (confirm('Delete ' + checked + ' application(s)? Related interviews and files will be removed.')) { bulkAction='delete'; run() }"
                            class="text-sm font-medium px-3 py-1.5 rounded-md bg-red-600 hover:bg-red-500 transition-colors">
                        Delete
                    </button>
                    <button type="button" x-show="bulkAction === 'set_status' && bulkStatus !== ''" @click="run()" :disabled="busy"
                            class="text-sm font-medium px-3 py-1.5 rounded-md bg-white text-gray-900 hover:bg-gray-100 disabled:opacity-50 transition-colors">
                        <span x-show="!busy">Apply to selected</span><span x-show="busy">Working…</span>
                    </button>
                </div>

            {{-- Dense table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        @php
                            $query = request()->query();
                            unset($query['sort']);
                        @endphp
                        <tr class="border-b border-gray-200">
                            <th class="px-3 py-2.5 w-10">
                                <input type="checkbox"
                                       @change="selectAll($el.checked)"
                                       class="align-middle h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-400">
                            </th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide px-4 py-2.5 whitespace-nowrap cursor-pointer select-none hover:text-gray-900 transition-colors" onclick="location.href='?sort=company&amp;{{ http_build_query($query) }}'" title="Sort by company">
                                Company <span class="{{ $sort === 'company' ? 'text-gray-900' : 'text-gray-300' }}">{{ $sort === 'company' ? '↓' : '↕' }}</span>
                            </th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide px-4 py-2.5 whitespace-nowrap">Role</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide px-4 py-2.5 whitespace-nowrap">Status</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide px-4 py-2.5 whitespace-nowrap">Salary range</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide px-4 py-2.5 whitespace-nowrap cursor-pointer select-none hover:text-gray-900 transition-colors" onclick="location.href='?sort={{ $sort === 'newest' ? 'oldest' : 'newest' }}&amp;{{ http_build_query($query) }}'" title="Toggle date order">
                                Date <span class="{{ in_array($sort, ['newest', 'oldest']) ? 'text-gray-900' : 'text-gray-300' }}">{{ $sort === 'oldest' ? '↑' : ($sort === 'newest' ? '↓' : '↕') }}</span>
                            </th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wide px-4 py-2.5 whitespace-nowrap">Contact</th>
                            <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wide px-4 py-2.5 w-24">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($applications as $a)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-3 py-3">
                                    <input type="checkbox" name="ids[]" value="{{ $a->id }}"
                                           @change="sync()" class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-400">
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        @if (in_array($a->id, $staleIds ?? []))
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500 shrink-0" title="Stalled — no movement for a while"></span>
                                        @endif
                                        <a href="{{ route('applications.show', $a) }}" class="font-medium text-gray-900 hover:text-gray-600 transition-colors">
                                            {{ $a->company }}
                                        </a>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ $a->title }}</td>
                                <td class="px-4 py-3">
                                    <div x-data="{
                                        status: @js($a->status),
                                        saving: false,
                                        updated: false
                                      }">
                                        <select x-model="status"
                                                @change="
                                                    saving = true;
                                                    fetch('/applications/{{ $a->id }}/status', {
                                                        method: 'POST',
                                                        headers: {'Content-Type':'application/json','X-CSRF-TOKEN': @js(csrf_token()), 'X-Requested-With':'XMLHttpRequest'},
                                                        body: JSON.stringify({ status: status })
                                                    }).then(r => {
                                                        if (r.ok) {
                                                            updated = true
                                                            setTimeout(() => { window.location.reload() }, 600)
                                                        }
                                                    }).finally(() => saving = false)
                                                "
                                                class="text-xs font-medium border border-gray-200 bg-gray-50 hover:border-gray-300 hover:bg-white rounded-md px-2 py-1 pr-6 focus:outline-none focus:border-gray-400 cursor-pointer transition-colors {{ $a->status === 'Offered' ? 'text-green-700 bg-green-50 border-green-200' : (($a->status === 'Rejected' || $a->status === 'Ghosted') ? 'text-red-600 bg-red-50 border-red-200' : 'text-gray-700') }}">
                                            @foreach ($statuses as $s)
                                                <option value="{{ $s }}" @selected($a->status === $s)>{{ $s }}</option>
                                            @endforeach
                                        </select>
                                        <span x-show="updated" x-cloak class="ml-2 inline-block text-[11px] text-emerald-600">✓ saved</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-500 tabular-nums whitespace-nowrap">{{ $a->salary_range ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-500 tabular-nums whitespace-nowrap">{{ $a->application_date?->format('d M Y') ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-500">
                                    @if ($a->contact)
                                        <span class="block max-w-[220px] truncate" title="{{ $a->contact }}">{{ $a->contact }}</span>
                                    @else
                                        <span>—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('applications.show', $a) }}" class="text-xs font-medium text-gray-500 hover:text-gray-900 transition-colors">View</a>
                                        <a href="{{ route('applications.edit', $a) }}" class="text-xs font-medium text-gray-500 hover:text-gray-900 transition-colors">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <x-empty-state
                                        :title="$search ? 'No applications match your filter' : 'No applications yet'"
                                        :description="$search ? 'Try a different search or clear the filters.' : 'Start by adding your first job application.'"
                                        action="{{ route('applications.create') }}"
                                        action-label="New application" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($applications->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $applications->withQueryString()->links('pagination::tailwind') }}
                </div>
            @endif
        </div>

        <p class="mt-3 text-xs text-gray-400">
            {{ $applications->total() }} {{ Str::plural('application', $applications->total()) }}
            @if($selectedStatus) · filtered by {{ $selectedStatus }} @endif
        </p>
    </div>
</x-app-layout>
