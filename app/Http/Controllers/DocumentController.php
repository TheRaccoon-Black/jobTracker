<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentRequest;
use App\Models\Application;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(Request $request): View
    {
        $documents = Document::with('application')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(12);

        return view('documents.index', [
            'documents' => $documents,
            'types' => Document::TYPES,
            'applications' => Application::where('user_id', $request->user()->id)->get(['id', 'company', 'title']),
            'selectedType' => $request->input('type'),
        ]);
    }

    public function store(DocumentRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('documents', 'local');
            $data['file_path'] = $path;
            $data['file_name'] = $request->file('file')->getClientOriginalName();
        }

        $request->user()->documents()->create($data);
        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    public function destroy(Request $request, Document $document)
    {
        abort_unless($document->user_id === $request->user()->id, 403);
        Storage::disk('local')->delete($document->file_path);
        $document->delete();
        return redirect()->route('documents.index')->with('success', 'Dokumen dihapus.');
    }

    public function download(Request $request, Document $document)
    {
        abort_unless($document->user_id === $request->user()->id, 403);
        return Storage::disk('local')->download($document->file_path);
    }
}
