@props(['statuses', 'applications', 'token'])

@php
    $cols = $statuses;
    $boardJson = [
        'token' => $token,
        'columns' => $cols,
        // $applications is groupBy('status') => keys are the status labels
        'items' => $applications->flatMap(function ($group, $status) {
            return $group->map(fn ($a) => [
                'id' => $a->id,
                'company' => $a->company,
                'title' => $a->title,
                'status' => $status,
                'date' => $a->application_date?->format('d M Y'),
                'salary' => $a->salary_range,
            ])->values()->all();
        })->all(),
    ];
@endphp

<div
    x-data="{
        token: @js($boardJson['token']),
        columns: @js($boardJson['columns']),
        items: @js($boardJson['items']),
        dragId: null,
        overCol: null,
        flash: '',
        cols(status) {
            return this.items.filter(i => i.status === status)
        },
        startDrag(e, id) {
            this.dragId = id
            e.dataTransfer.effectAllowed = 'move'
            e.dataTransfer.setData('text/plain', String(id))
        },
        onDrop(status) {
            const id = Number(this.dragId)
            const item = this.items.find(i => i.id === id)
            this.dragId = null
            this.overCol = null
            if (!item || item.status === status) return
            const prev = item.status
            item.status = status
            fetch(`/applications/${id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.token,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ status }),
            }).then(r => {
                if (!r.ok) {
                    item.status = prev
                    this.pushFlash('failed')
                } else {
                    this.pushFlash('moved')
                }
            })
        },
        pushFlash(type) {
            this.flash = type === 'moved' ? 'Stage updated.' : 'Update failed — check the network.'
            setTimeout(() => { this.flash = '' }, 2500)
        }
    }"
    class="flex gap-4 items-start overflow-x-auto pb-4 -mx-6 px-6 lg:mx-0 lg:px-0"
    style="min-width: min(100%, 1200px);">

    <template x-for="col in columns" :key="col">
        <div
            :class="overCol === col ? 'border-gray-900 bg-gray-100' : 'border-gray-200 bg-white'"
            class="flex flex-col w-72 shrink-0 border rounded-lg transition-colors"
            @dragover.prevent="overCol = col"
            @dragleave.self="overCol = null"
            @drop.prevent="onDrop(col)">

            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
                <span class="text-xs font-semibold text-gray-900 uppercase tracking-wide" x-text="col"></span>
                <span class="text-xs text-gray-400 tabular-nums bg-gray-50 border border-gray-200 rounded px-1.5 py-0.5"
                      x-text="cols(col).length"></span>
            </div>

            <div class="flex-1 min-h-[80px] p-2 space-y-2">
                <template x-for="item in cols(col)" :key="item.id">
                    <div
                        draggable="true"
                        @dragstart="startDrag($event, item.id)"
                        @dragend="dragId = null"
                        :class="dragId === item.id ? 'opacity-40' : 'opacity-100'"
                        class="group border border-gray-200 rounded-md bg-white p-3 cursor-grab active:cursor-grabbing hover:border-gray-300 transition-colors">
                        <div class="flex items-start justify-between gap-2">
                            <a :href="'/applications/' + item.id" class="text-sm font-medium text-gray-900 hover:underline underline decoration-gray-200 underline-offset-2 truncate" x-text="item.company"></a>
                        </div>
                        <div class="text-xs text-gray-500 mt-0.5 truncate" x-text="item.title"></div>
                        <div class="flex items-center gap-3 mt-2.5 text-[11px] text-gray-400">
                            <span x-show="item.date" x-text="item.date" class="tabular-nums"></span>
                            <span x-show="item.salary" x-text="item.salary" class="tabular-nums"></span>
                        </div>
                    </div>
                </template>
                <template x-if="cols(col).length === 0">
                    <div class="border border-dashed border-gray-200 rounded-md py-6 text-center text-xs text-gray-300">
                        Empty
                    </div>
                </template>
            </div>
        </div>
    </template>

    <div x-show="flash" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="fixed bottom-4 right-4 z-50 bg-gray-900 text-white text-sm px-4 py-2.5 rounded-lg border border-gray-700">
        <span x-text="flash"></span>
    </div>
</div>
