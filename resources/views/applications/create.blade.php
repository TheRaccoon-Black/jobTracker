<x-app-layout>
    <div class="px-6 lg:px-8 py-6 max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('applications.index') }}" class="text-sm text-gray-500 hover:text-gray-900 transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5l-7.5-7.5 7.5-7.5"/></svg>
                Applications
            </a>
            <h1 class="text-lg font-semibold text-gray-900 tracking-tight mt-3">New application</h1>
            <p class="text-sm text-gray-500 mt-0.5">Track a new job application.</p>
        </div>

        @if ($errors->any())
            <div class="border border-red-200 bg-red-50 rounded-md p-4 mb-5 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('applications.store') }}" class="border border-gray-200 rounded-lg bg-white">
            @csrf
            <div class="px-5 py-4 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="company" class="block text-xs font-medium text-gray-500 mb-1.5">Company <span class="text-red-500">*</span></label>
                        <input type="text" id="company" name="company" value="{{ old('company') }}" required
                               class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400">
                    </div>
                    <div>
                        <label for="title" class="block text-xs font-medium text-gray-500 mb-1.5">Role <span class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="status" class="block text-xs font-medium text-gray-500 mb-1.5">Status <span class="text-red-500">*</span></label>
                        <select id="status" name="status" required
                                class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 bg-white focus:outline-none focus:border-gray-400">
                            @foreach ($statuses as $s)
                                <option value="{{ $s }}" @selected(old('status', 'Wishlist') === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="application_date" class="block text-xs font-medium text-gray-500 mb-1.5">Application date</label>
                        <input type="date" id="application_date" name="application_date" value="{{ old('application_date', now()->format('Y-m-d')) }}"
                               class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-gray-400">
                    </div>
                </div>

                <div>
                    <label for="job_posting_link" class="block text-xs font-medium text-gray-500 mb-1.5">Job posting link</label>
                    <input type="url" id="job_posting_link" name="job_posting_link" value="{{ old('job_posting_link') }}"
                           class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400">
                    <p class="text-xs text-gray-400 mt-1.5">Save the link in case the posting goes offline.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="contact" class="block text-xs font-medium text-gray-500 mb-1.5">Contact / recruiter</label>
                        <input type="text" id="contact" name="contact" value="{{ old('contact') }}"
                               class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400"
                               placeholder="Name, email or phone">
                    </div>
                    <div>
                        <label for="salary_range" class="block text-xs font-medium text-gray-500 mb-1.5">Salary range</label>
                        <input type="text" id="salary_range" name="salary_range" value="{{ old('salary_range') }}"
                               class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400"
                               placeholder="e.g. Rp 15–20 jt / month">
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-xs font-medium text-gray-500 mb-1.5">Notes</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400">{{ old('notes') }}</textarea>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="tags" class="block text-xs font-medium text-gray-500">Tags</label>
                        <span class="text-[11px] text-gray-400">Optional, comma-separated</span>
                    </div>
                    <input type="text" id="tags" name="tags" value="{{ old('tags') }}"
                           placeholder="e.g. remote, fintech, senior"
                           class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400">
                    @if (old('tags'))
                        @foreach (array_slice((array) old('tags'), 1) as $i => $t)
                            @if ($t)
                            @endif
                        @endforeach
                    @endif
                    <p class="text-xs text-gray-400 mt-1.5">Tag a few attributes you care about (work style, industry, level) to filter later.</p>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-medium text-gray-500">Follow-up reminder</label>
                        <span class="text-[11px] text-gray-400">Optional</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="next_follow_up" class="sr-only">Next follow-up date</label>
                            <input type="date" id="next_follow_up" name="next_follow_up" value="{{ old('next_follow_up') }}"
                                   class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400">
                        </div>
                        <div>
                            <label for="follow_up_notes" class="sr-only">Follow-up notes</label>
                            <input type="text" id="follow_up_notes" name="follow_up_notes" value="{{ old('follow_up_notes') }}"
                                   placeholder="e.g. ask about hiring timeline"
                                   class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-400">
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1.5">When the date arrives, this application appears under "Follow-ups due" on your dashboard.</p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 px-5 py-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
                <a href="{{ route('applications.index') }}" class="text-sm font-medium text-gray-600 px-3.5 py-2 border border-gray-300 rounded-md hover:bg-white transition-colors">Cancel</a>
                <button type="submit" class="text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 px-3.5 py-2 rounded-md transition-colors">Save application</button>
            </div>
        </form>
    </div>
</x-app-layout>
