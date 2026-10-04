@props(['applications'])

@php
    $paletteApps = $applications->map(fn ($a) => [
        'id' => $a->id,
        'company' => $a->company,
        'title' => $a->title,
        'status' => $a->status,
    ])->values()->all();
@endphp

<div x-data="jobPalette"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-50 bg-gray-950/40">
    <template x-if="open">
        <div class="relative mx-auto mt-24 sm:mt-32 max-w-xl px-4" @click.outside="open = false">
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <div class="flex items-center gap-3 px-4 border-b border-gray-200">
                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.25-5.25M17.25 13.5L21 17.25M15.75 6.75a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <input type="text" x-ref="searchInput" x-model="query"
                           @input="selected = 0"
                           placeholder="Search applications or jump to…"
                           class="w-full py-3.5 text-sm focus:outline-none placeholder:text-gray-400">
                    <kbd class="hidden sm:block text-[11px] text-gray-400 border border-gray-200 rounded px-1.5 py-0.5 shrink-0">ESC</kbd>
                </div>

                <div class="max-h-80 overflow-y-auto py-1.5">
                    <template x-for="(item, i) in filtered" :key="item.label + i">
                        <a :href="item.href" @click="open = false"
                           :class="i === selected ? 'bg-gray-100' : 'hover:bg-gray-50'"
                           class="flex items-center gap-3 px-4 py-2.5 transition-colors">
                            <span class="h-6 w-6 rounded bg-gray-100 border border-gray-200 flex items-center justify-center shrink-0">
                                <template x-if="item.href.startsWith('/applications/')">
                                    <span class="text-[10px] font-semibold text-gray-500 uppercase" x-text="item.label.split(' — ')[0].slice(0, 2)"></span>
                                </template>
                                <template x-else>
                                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                </template>
                            </span>
                            <span class="text-sm font-medium text-gray-900 truncate flex-1 min-w-0" x-text="item.label"></span>
                            <span class="text-xs text-gray-400 shrink-0" x-text="item.hint"></span>
                        </a>
                    </template>
                    <template x-if="filtered.length === 0">
                        <div class="px-4 py-10 text-center text-sm text-gray-400">No results found.</div>
                    </template>
                </div>

                <div class="flex items-center justify-between border-t border-gray-200 px-4 py-2 text-[11px] text-gray-400">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center gap-1"><kbd class="border border-gray-200 rounded px-1 py-0.5 bg-gray-50">↑</kbd><kbd class="border border-gray-200 rounded px-1 py-0.5 bg-gray-50">↓</kbd>&nbsp;navigate</span>
                        <span class="flex items-center gap-1"><kbd class="border border-gray-200 rounded px-1 py-0.5 bg-gray-50">↵</kbd>&nbsp;open</span>
                    </div>
                    <span>JobTracker Pro</span>
                </div>
            </div>
        </div>
    </template>
</div>

<script type="application/json" id="jt-palette-data">
    @json($paletteApps)
</script>

<script>
    window.jobPalette = function () {
        const dataEl = document.getElementById('jt-palette-data')
        return {
            open: false,
            query: '',
            selected: 0,
            apps: dataEl ? JSON.parse(dataEl.textContent) : [],
            init() {
                window.addEventListener('keydown', (e) => {
                    if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
                        e.preventDefault()
                        this.open = !this.open
                        this.query = ''
                        this.selected = 0
                        if (this.open) setTimeout(() => this.$refs.searchInput && this.$refs.searchInput.focus(), 50)
                        return
                    }
                    if (!this.open) return
                    if (e.key === 'Escape') this.open = false
                    if (e.key === 'ArrowDown') { e.preventDefault(); this.selected = Math.min(this.selected + 1, this.filtered.length - 1) }
                    if (e.key === 'ArrowUp') { e.preventDefault(); this.selected = Math.max(this.selected - 1, 0) }
                    if (e.key === 'Enter' && this.filtered.length) {
                        const target = this.filtered[this.selected]
                        if (target) { window.location.href = target.href; this.open = false }
                    }
                })
            },
            actions: [
                { label: 'Dashboard', hint: 'Navigation', href: '{{ route('dashboard') }}' },
                { label: 'New Application', hint: 'Action', href: '{{ route('applications.create') }}' },
                { label: 'All Applications', hint: 'Navigation', href: '{{ route('applications.index') }}' },
                { label: 'Interviews', hint: 'Navigation', href: '{{ route('interviews.index') }}' },
                { label: 'Documents', hint: 'Navigation', href: '{{ route('documents.index') }}' },
                { label: 'Profile & Settings', hint: 'Navigation', href: '{{ route('profile.edit') }}' },
                { label: 'Guide & Shortcuts', hint: 'Help', href: '{{ route('guide') }}' },
            ],
            get filtered() {
                const q = this.query.toLowerCase()
                const appItems = this.apps.map(a => ({
                    label: a.company + ' — ' + a.title,
                    hint: a.status,
                    href: '/applications/' + a.id,
                }))
                const all = this.actions.concat(appItems)
                if (!q) return all
                return all.filter(item => item.label.toLowerCase().includes(q) || (item.hint || '').toLowerCase().includes(q))
            },
        }
    }
</script>
