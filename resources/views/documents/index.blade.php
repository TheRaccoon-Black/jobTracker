<x-app-layout>
    <div class="px-6 lg:px-8 py-6 max-w-5xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-lg font-semibold text-gray-900 tracking-tight">Documents</h1>
                <p class="text-sm text-gray-500 mt-0.5">CVs, portfolios, and cover letters in one place.</p>
            </div>
            <button type="button" onclick="document.getElementById('doc-upload').classList.toggle('hidden')"
                   class="inline-flex items-center gap-1.5 text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 px-3.5 py-2 rounded-md transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Upload document
            </button>
        </div>

        @if (session('success'))
            <div class="mb-5 border border-emerald-200 bg-emerald-50 rounded-md px-4 py-2.5 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        <div id="doc-upload" class="hidden mb-5 border border-gray-200 rounded-lg bg-white">
            <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="px-5 py-4">
                @csrf
                @if ($errors->any())
                    <div class="mb-3 border border-red-200 bg-red-50 rounded-md p-3 text-xs text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Type <span class="text-red-500">*</span></label>
                        <select name="type" required class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 bg-white focus:outline-none focus:border-gray-400">
                            @foreach ($types as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Link to application</label>
                        <select name="application_id" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 bg-white focus:outline-none focus:border-gray-400">
                            <option value="">— Not linked —</option>
                            @foreach ($applications as $app)
                                <option value="{{ $app->id }}">{{ $app->company }} — {{ $app->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">File</label>
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.png,.jpg,.jpeg,.zip"
                               class="w-full text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 file:cursor-pointer">
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-3">PDF, DOC(X), PPT(X), XLS(X), TXT, PNG/JPG, ZIP. Max 5 MB.</p>
                <div class="flex gap-2 mt-4">
                    <button type="submit" class="text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 px-3.5 py-2 rounded-md transition-colors">Upload</button>
                    <button type="button" onclick="document.getElementById('doc-upload').classList.add('hidden')" class="text-sm font-medium text-gray-600 px-3.5 py-2 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">Close</button>
                </div>
            </form>
        </div>

        <form method="GET" action="{{ route('documents.index') }}" class="mb-4 flex gap-3 items-center">
            <select name="type" class="text-sm border border-gray-300 rounded-md px-3 py-1.5 bg-white focus:outline-none focus:border-gray-400">
                <option value="">All types</option>
                @foreach ($types as $t)
                    <option value="{{ $t }}" @selected($selectedType === $t)>{{ $t }}</option>
                @endforeach
            </select>
            <button type="submit" class="text-sm font-medium text-gray-700 px-3 py-1.5 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">Filter</button>
        </form>

        @if ($documents->isEmpty())
            <div class="border border-gray-200 rounded-lg bg-white py-16 text-center">
                <p class="text-sm text-gray-400">No documents yet.</p>
            </div>
        @else
            <div class="border border-gray-200 rounded-lg bg-white divide-y divide-gray-200">
                @foreach ($documents as $doc)
                    <div class="flex items-center gap-4 px-5 py-3.5">
                        <div class="min-w-0 flex-1">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $doc->file_name }}</div>
                            <div class="flex items-center gap-2 mt-1 text-xs text-gray-500">
                                <span class="px-1.5 py-0.5 bg-gray-100 border border-gray-200 rounded text-gray-600 font-medium">{{ $doc->type }}</span>
                                @if ($doc->application)
                                    <a href="{{ route('applications.show', $doc->application) }}" class="text-gray-500 hover:text-gray-900 hover:underline underline decoration-gray-300 underline-offset-2 transition-colors truncate">
                                        {{ $doc->application->company }} — {{ $doc->application->title }}
                                    </a>
                                @endif
                                <span class="tabular-nums">{{ $doc->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 shrink-0">
                            <a href="{{ route('documents.download', $doc) }}" class="text-xs font-medium text-gray-500 hover:text-gray-900 transition-colors">Download</a>
                            <form method="POST" action="{{ route('documents.destroy', $doc) }}" onsubmit="return confirm('Delete this document?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-medium text-gray-400 hover:text-red-600 transition-colors">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($documents->hasPages())
                <div class="mt-4">{{ $documents->links() }}</div>
            @endif
        @endif
    </div>
</x-app-layout>
