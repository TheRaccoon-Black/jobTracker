<x-app-layout>
    <div class="px-6 lg:px-8 py-6 max-w-6xl">

        @if (session('success'))
            <div class="mb-5 border border-emerald-200 bg-emerald-50 rounded-md px-4 py-2.5 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Header row --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4 min-w-0">
                <div class="h-10 w-10 rounded-md bg-gray-900 text-white flex items-center justify-center text-sm font-semibold uppercase shrink-0">
                    {{ strtoupper(substr($application->company, 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-lg font-semibold text-gray-900 tracking-tight truncate">{{ $application->company }}</h1>
                        <x-status-badge :status="$application->status" />
                    </div>
                    <p class="text-sm text-gray-500 mt-0.5 truncate">{{ $application->title }}
                        @if ($application->application_date)
                            · applied {{ $application->application_date->format('d M Y') }}
                        @endif
                    </p>
                    @if (!empty($application->tags))
                        <div class="flex flex-wrap gap-1.5 mt-1.5">
                            @foreach ($application->tags as $tag)
                                <a href="{{ route('applications.index', ['tags' => $tag]) }}"
                                   class="inline-flex items-center gap-1 text-[11px] font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-full px-2 py-0.5 transition-colors">
                                    #{{ $tag }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('applications.edit', $application) }}"
                   class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-700 border border-gray-300 rounded-md px-3 py-1.5 hover:bg-gray-50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.802-2.685a4.5 4.5 0 011.13-1.897L16.862 4.487z"/></svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('applications.destroy', $application) }}" onsubmit="return confirm('Delete this application and its interviews?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1.5 text-sm font-medium text-red-600 border border-red-200 rounded-md px-3 py-1.5 hover:bg-red-50 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.162m-1.022-.162L23.494 4.97a1.125 1.125 0 00-1.278-.511L20.5 4.75m-5.441 5.44L15.37 9.87c-.355.237-.685.509-.976.809L13.5 12"/></svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            {{-- Left column --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Details --}}
                <div class="border border-gray-200 rounded-lg bg-white">
                    <div class="px-5 py-3.5 border-b border-gray-200">
                        <h2 class="text-sm font-medium text-gray-900">Details</h2>
                    </div>
                    <dl class="px-5 py-4 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                        <div>
                            <dt class="text-xs text-gray-500 mb-1">Job posting</dt>
                            <dd class="text-sm text-gray-900">
                                @if ($application->job_posting_link)
                                    <a href="{{ $application->job_posting_link }}" target="_blank" rel="noopener" class="text-gray-900 hover:underline underline decoration-gray-300 underline-offset-2 break-all">{{ $application->job_posting_link }}</a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 mb-1">Contact / recruiter</dt>
                            <dd class="text-sm text-gray-900">{{ $application->contact ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 mb-1">Salary range</dt>
                            <dd class="text-sm text-gray-900 tabular-nums">{{ $application->salary_range ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 mb-1">Applied</dt>
                            <dd class="text-sm text-gray-900 tabular-nums">{{ $application->application_date?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        @if ($application->notes)
                            <div class="sm:col-span-2">
                                <dt class="text-xs text-gray-500 mb-1">Notes</dt>
                                <dd class="text-sm text-gray-700 whitespace-pre-line">{{ $application->notes }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Interviews --}}
                <div class="border border-gray-200 rounded-lg bg-white">
                    <div class="px-5 py-3.5 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="text-sm font-medium text-gray-900">Stages &amp; interviews</h2>
                        <button type="button" onclick="document.getElementById('iv-toggle').classList.toggle('hidden')"
                                 class="text-xs font-medium text-gray-900 border border-gray-300 rounded-md px-2.5 py-1 hover:bg-gray-50 transition-colors">
                            + Add stage
                        </button>
                    </div>

                    <div id="iv-toggle" class="hidden border-b border-gray-200 bg-gray-50 px-5 py-4">
                        <form method="POST" action="{{ route('applications.interviews.store', $application) }}" class="space-y-3">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <select name="interview_stage" required
                                        class="text-sm border border-gray-300 rounded-md px-3 py-2 bg-white focus:outline-none focus:border-gray-400">
                                    @foreach (\App\Models\Interview::STAGES as $stage)
                                        <option value="{{ $stage }}">{{ $stage }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="interviewer" placeholder="Interviewer &amp; title"
                                       class="text-sm border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-gray-400">
                                <input type="datetime-local" name="schedule_date"
                                       class="text-sm border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-gray-400">
                                <input type="url" name="link_meeting" placeholder="Meeting link (Zoom / Meet)"
                                       class="text-sm border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-gray-400">
                            </div>
                            <textarea name="notes" rows="2" placeholder="Notes"
                                      class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-gray-400"></textarea>
                            @if ($errors->first('interview_stage'))
                                <p class="text-xs text-red-600">{{ $errors->first('interview_stage') }}</p>
                            @endif
                            <div class="flex gap-2">
                                <button type="submit" class="text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 px-3 py-1.5 rounded-md transition-colors">Save</button>
                                <button type="button" onclick="document.getElementById('iv-toggle').classList.add('hidden')"
                                        class="text-sm font-medium text-gray-600 px-3 py-1.5 border border-gray-300 rounded-md hover:bg-white transition-colors">Cancel</button>
                            </div>
                        </form>
                    </div>

                    @if ($application->interviews->isEmpty())
                        <p class="px-5 py-8 text-sm text-gray-400 text-center">No interview stages recorded yet.</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($application->interviews as $interview)
                                <li class="px-5 py-3.5 flex flex-col sm:flex-row sm:items-center gap-2">
                                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                        <span class="h-2 w-2 rounded-full shrink-0 {{ $interview->schedule_date && $interview->schedule_date->isFuture() ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                        <div class="min-w-0">
                                            <div class="text-sm font-medium text-gray-900">{{ $interview->interview_stage }}</div>
                                            @if ($interview->interviewer)
                                                <div class="text-xs text-gray-500 mt-0.5">{{ $interview->interviewer }}</div>
                                            @endif
                                            @if ($interview->notes)
                                                <div class="text-xs text-gray-400 mt-0.5 truncate">{{ $interview->notes }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4 text-xs text-gray-500 sm:ml-auto">
                                        @if ($interview->schedule_date)
                                            <span class="tabular-nums">{{ $interview->schedule_date->format('d M Y · H:i') }}</span>
                                        @endif
                                        @if ($interview->link_meeting)
                                            <a href="{{ $interview->link_meeting }}" target="_blank" rel="noopener" class="hover:underline underline decoration-gray-300 underline-offset-2">Join</a>
                                        @endif
                                        <form method="POST" action="{{ route('applications.interviews.destroy', [$application, $interview]) }}" onsubmit="return confirm('Delete this stage?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">Remove</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            {{-- Right column --}}
            <div class="space-y-5">
                <div class="border border-gray-200 rounded-lg bg-white">
                    <div class="px-5 py-3.5 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="text-sm font-medium text-gray-900">Documents</h2>
                        <a href="{{ route('documents.index') }}" class="text-xs text-gray-500 hover:text-gray-900 transition-colors">Manage</a>
                    </div>
                    @if ($application->documents->isEmpty())
                        <p class="px-5 py-6 text-sm text-gray-400 text-center">No documents linked to this application.</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($application->documents as $doc)
                                <li class="px-5 py-3 flex items-center gap-3">
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-medium text-gray-900 truncate">{{ $doc->file_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $doc->type }}</div>
                                    </div>
                                    <a href="{{ route('documents.download', $doc) }}" class="text-xs font-medium text-gray-500 hover:text-gray-900 transition-colors shrink-0">Download</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="border border-gray-200 rounded-lg bg-white">
                    <div class="px-5 py-3.5 border-b border-gray-200">
                        <h2 class="text-sm font-medium text-gray-900">Follow-up</h2>
                        @if ($application->next_follow_up && $application->next_follow_up->isPast() && !$application->next_follow_up->isToday())
                            <span class="text-[11px] font-medium text-red-600 bg-red-50 border border-red-100 rounded px-1.5 py-0.5">Overdue</span>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('applications.follow-up.update', $application) }}" class="px-5 py-4 space-y-3">
                        @csrf
                        <div>
                            <label for="next_follow_up" class="block text-xs text-gray-500 mb-1.5">Next follow-up date</label>
                            <input type="date" id="next_follow_up" name="next_follow_up"
                                   value="{{ old('next_follow_up', $application->next_follow_up?->format('Y-m-d')) }}"
                                   class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400">
                        </div>
                        <div>
                            <label for="follow_up_notes" class="block text-xs text-gray-500 mb-1.5">Notes</label>
                            <input type="text" id="follow_up_notes" name="follow_up_notes"
                                   value="{{ old('follow_up_notes', $application->follow_up_notes) }}"
                                   placeholder="e.g. ask about hiring timeline"
                                   class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400">
                        </div>
                        <button type="submit" class="w-full text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 px-3 py-2 rounded-md transition-colors">Save follow-up</button>
                    </form>
                    <div x-data="{ open: false, busy: false, draft: null,
                                 async load() {
                                     if (this.draft) { this.open = true; return }
                                     this.busy = true
                                     const r = await fetch('{{ route('applications.follow-up.draft', $application) }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                                     this.draft = await r.json()
                                     this.busy = false
                                     this.open = true
                                 } }"
                         class="border-t border-gray-200 px-5 py-4">
                        <button type="button" @click="load()" :disabled="busy"
                                class="w-full inline-flex items-center justify-center gap-1.5 text-sm font-medium text-gray-700 border border-gray-300 rounded-md px-3 py-2 hover:bg-gray-50 disabled:opacity-50 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            <span x-show="!busy">Draft follow-up email</span>
                            <span x-show="busy">Writing…</span>
                        </button>
                        <div x-show="open" x-cloak x-transition class="mt-3">
                            <template x-if="draft">
                                <div class="space-y-2">
                                    <div class="text-xs text-gray-500">Subject</div>
                                    <div class="text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-md px-3 py-2" x-text="draft.subject"></div>
                                    <div class="text-xs text-gray-500">Body</div>
                                    <textarea x-text="draft.body" rows="10" readonly
                                              class="w-full text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-md px-3 py-2 leading-relaxed"></textarea>
                                    <a :href="'mailto:' + encodeURIComponent(draft.to || '') + '?subject=' + encodeURIComponent(draft.subject) + '&body=' + encodeURIComponent(draft.body)"
                                       class="block text-center text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 rounded-md px-3 py-2 transition-colors">
                                        Open in mail app
                                    </a>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-lg bg-white">
                    <div class="px-5 py-3.5 border-b border-gray-200">
                        <h2 class="text-sm font-medium text-gray-900">Summary</h2>
                    </div>
                    <dl class="px-5 py-4 space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <dt class="text-gray-500">Current stage</dt>
                            <dd class="font-medium text-gray-900">{{ $application->interviews->first()?->interview_stage ?? $application->status }}</dd>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <dt class="text-gray-500">Interviews</dt>
                            <dd class="font-medium text-gray-900 tabular-nums">{{ $application->interviews->count() }}</dd>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <dt class="text-gray-500">Documents</dt>
                            <dd class="font-medium text-gray-900 tabular-nums">{{ $application->documents->count() }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Activity log --}}
                <div class="border border-gray-200 rounded-lg bg-white">
                    <div class="px-5 py-3.5 border-b border-gray-200">
                        <h2 class="text-sm font-medium text-gray-900">Activity</h2>
                    </div>
                    @if ($application->logs->isEmpty())
                        <p class="px-5 py-5 text-sm text-gray-400 text-center">No activity recorded yet.</p>
                    @else
                        <ul class="divide-y divide-gray-100">
                            @foreach ($application->logs as $log)
                                <li class="px-5 py-3">
                                    <div class="text-sm text-gray-900">
                                        @php
                                            $label = [
                                                'created' => 'Application created',
                                                'status_changed' => 'Status changed',
                                                'follow_up_set' => 'Follow-up updated',
                                                'interview_added' => 'Interview stage added',
                                            ][$log->event] ?? ($log->event . ' ' . $log->new_value);
                                        @endphp
                                        {{ $label }}
                                    </div>
                                    @if ($log->event === 'status_changed' && $log->old_value)
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            {{ $log->old_value }} → <span class="font-medium text-gray-700">{{ $log->new_value }}</span>
                                        </div>
                                    @elseif ($log->event === 'interview_added' && $log->new_value)
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $log->new_value }}</div>
                                    @endif
                                    @if ($log->note)
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $log->note }}</div>
                                    @endif
                                    <div class="text-xs text-gray-400 mt-1 tabular-nums">{{ $log->created_at->format('d M Y · H:i') }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
