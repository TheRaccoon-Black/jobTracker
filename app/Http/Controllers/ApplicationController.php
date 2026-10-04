<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationRequest;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $query = $this->applicationsQuery($request);

        $applications = $query
            ->with(['interviews' => fn ($i) => $i->orderByDesc('schedule_date')->limit(1), 'documents'])
            ->paginate(10)
            ->withQueryString();

        $staleIds = Application::where('user_id', $request->user()->id)
            ->stale()
            ->pluck('id')
            ->all();

        return view('applications.index', [
            'applications' => $applications,
            'statuses' => Application::STATUSES,
            'selectedStatus' => $request->input('status'),
            'search' => $request->input('search'),
            'selectedTags' => $request->input('tags'),
            'allTags' => Application::where('user_id', $request->user()->id)
                ->whereNotNull('tags')->pluck('tags')
                ->flatMap(fn ($t) => is_array($t) ? $t : json_decode($t ?: '[]', true))
                ->unique()->sort()->values()->all(),
            'sort' => $request->input('sort', 'newest'),
            'staleIds' => $staleIds,
        ]);
    }

    public function export(Request $request)
    {
        $rows = $this->applicationsQuery($request)
            ->without(['interviews', 'documents'])
            ->limit(5000)
            ->get(['company', 'title', 'status', 'salary_range', 'application_date', 'contact', 'job_posting_link', 'notes']);

        $file = 'php://temp';
        $handle = fopen($file, 'w+');
        fputcsv($handle, ['Company', 'Role', 'Status', 'Salary Range', 'Application Date', 'Contact', 'Job Posting Link', 'Notes']);
        foreach ($rows as $row) {
            fputcsv($handle, [
                $row->company,
                $row->title,
                $row->status,
                $row->salary_range,
                $row->application_date?->format('Y-m-d'),
                $row->contact,
                $row->job_posting_link,
                $row->notes,
            ]);
        }
        rewind($handle);

        return response()->streamDownload(
            function () use ($handle) {
                fpassthru($handle);
            },
            'applications-' . now()->format('Ymd-His') . '.csv',
            ['Content-Type' => 'text/csv']
        );
    }

    public function create(): View
    {
        return view('applications.create', ['statuses' => Application::STATUSES]);
    }

    public function store(ApplicationRequest $request)
    {
        $validated = $request->validated();
        $validated['tags'] = $this->normalizeTags($validated['tags'] ?? null);
        $application = $request->user()->applications()->create($validated);
        $application->log('created', null, $application->status, null, $request->user()->id);
        return redirect()->route('applications.index')->with('success', 'Application added.');
    }

    public function show(Request $request, Application $application): View
    {
        $application->load(['interviews' => fn ($i) => $i->orderByDesc('schedule_date'), 'documents', 'logs']);
        abort_unless($application->user_id === $request->user()->id, 403);

        return view('applications.show', compact('application'));
    }

    public function edit(Request $request, Application $application): View
    {
        abort_unless($application->user_id === $request->user()->id, 403);
        return view('applications.edit', [
            'application' => $application,
            'statuses' => Application::STATUSES,
        ]);
    }

    public function update(ApplicationRequest $request, Application $application)
    {
        abort_unless($application->user_id === $request->user()->id, 403);

        $oldStatus = $application->status;
        $oldFollowUp = $application->next_follow_up?->format('Y-m-d') ?? null;
        $validated = $request->validated();
        $validated['tags'] = $this->normalizeTags($validated['tags'] ?? null);
        $application->update($validated);

        if ($oldStatus !== $application->status) {
            $application->log('status_changed', $oldStatus, $application->status, null, $request->user()->id);
        }
        $newFollowUp = $application->next_follow_up?->format('Y-m-d') ?? null;
        if ($oldFollowUp !== $newFollowUp) {
            $application->log('follow_up_set', $oldFollowUp, $newFollowUp, null, $request->user()->id);
        }

        return redirect()->route('applications.show', $application)->with('success', 'Application updated.');
    }

    public function destroy(Request $request, Application $application)
    {
        abort_unless($application->user_id === $request->user()->id, 403);
        $application->interviews()->delete();
        $application->delete();
        return redirect()->route('applications.index')->with('success', 'Application deleted.');
    }

    public function board(Request $request): View
    {
        $applications = Application::where('user_id', $request->user()->id)
            ->orderBy('company')
            ->get()
            ->groupBy('status');

        return view('applications.board', [
            'applications' => $applications,
            'statuses' => Application::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, Application $application)
    {
        abort_unless($application->user_id === $request->user()->id, 403);

        $validated = Validator::make($request->all(), [
            'status' => ['required', 'in:' . implode(',', Application::STATUSES)],
        ])->validate();

        $oldStatus = $application->status;
        $application->update($validated);
        if ($oldStatus !== $validated['status']) {
            $application->log('status_changed', $oldStatus, $validated['status'], null, $request->user()->id);
        }

        return back()->with('success', "Status updated to {$validated['status']}.");
    }

    public function updateFollowUp(Request $request, Application $application)
    {
        abort_unless($application->user_id === $request->user()->id, 403);

        $validated = Validator::make($request->all(), [
            'next_follow_up' => ['nullable', 'date'],
            'follow_up_notes' => ['nullable', 'string', 'max:2000'],
        ])->validate();

        $oldFollowUp = $application->next_follow_up?->format('Y-m-d') ?? null;
        $application->update([
            'next_follow_up' => $validated['next_follow_up'] ?? null,
            'follow_up_notes' => $validated['follow_up_notes'] ?? null,
        ]);
        $newFollowUp = $validated['next_follow_up'] ?? null;
        if ($oldFollowUp !== $newFollowUp) {
            $application->log('follow_up_set', $oldFollowUp, $newFollowUp, null, $request->user()->id);
        }

        return back()->with('success', 'Follow-up saved.');
    }

    /**
     * One-click follow-up email draft (GET, returns JSON).
     */
    public function draftFollowUp(Request $request, Application $application)
    {
        abort_unless($application->user_id === $request->user()->id, 403);

        $days = $application->application_date
            ? now()->diffInDays($application->application_date)
            : null;
        $company = $application->company;
        $role = $application->title;
        $contact = $application->contact ?: 'Hiring Team';
        $status = $application->status;
        $lastMove = $application->updated_at?->format('d M Y');

        $subject = "Following up: {$role} application at {$company}";

        $opening = $status === 'Offered'
            ? "I hope you're doing well. I wanted to kindly follow up on the offer for the {$role} position."
            : ($days !== null && $days >= 14
                ? "I hope this message finds you well. I applied for the {$role} position at {$company} on {$application->application_date->format('d F Y')}, and I wanted to check in on the status of my application."
                : "I hope you're doing well. I wanted to follow up on my application for the {$role} position at {$company}.");

        $extra = $status === 'Offered' ? '' : ', or if there is anything further you need from my side';
        $body = <<<TXT
{$opening}

I remain very interested in this opportunity and would love to know where things stand{$extra}.

Thank you for your time and consideration. I look forward to hearing from you.

Best regards,
{$request->user()->name}
TXT;

        return response()->json([
            'to' => $application->contact,
            'subject' => $subject,
            'body' => $body,
        ]);
    }

    /**
     * Bulk actions: status change or delete for many applications at once.
     */
    public function bulk(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'action' => ['required', 'in:set_status,delete'],
            'status' => ['required_if:action,set_status', 'in:' . implode(',', Application::STATUSES)],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:applications,id'],
        ])->validate();

        $apps = Application::where('user_id', $request->user()->id)
            ->whereIn('id', $validated['ids'])
            ->get();

        $count = 0;
        foreach ($apps as $app) {
            if ($validated['action'] === 'delete') {
                $app->interviews()->delete();
                $app->delete();
                $count++;
            } elseif ($validated['action'] === 'set_status' && $app->status !== $validated['status']) {
                $old = $app->status;
                $app->update(['status' => $validated['status']]);
                $app->log('status_changed', $old, $validated['status'], 'bulk', $request->user()->id);
                $count++;
            }
        }

        $msg = $validated['action'] === 'delete'
            ? "{$count} application(s) deleted."
            : "Status of {$count} application(s) updated to {$validated['status']}.";

        return back()->with('success', $msg);
    }

    protected function normalizeTags(?string $raw): array
    {
        if ($raw === null || $raw === '') {
            return [];
        }
        $parts = array_map(fn ($p) => mb_strtolower(trim($p)), explode(',', $raw));
        return array_values(array_unique(array_filter($parts, fn ($p) => $p !== '')));
    }

    protected function applicationsQuery(Request $request)
    {
        $q = Application::where('user_id', $request->user()->id);

        if ($request->filled('status') && in_array($request->status, Application::STATUSES)) {
            $q->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($w) use ($s) {
                $w->where('company', 'like', "%{$s}%")->orWhere('title', 'like', "%{$s}%");
            });
        }
        if ($request->filled('tags')) {
            $tags = array_filter(array_map('trim', explode(',', $request->tags)));
            if ($tags) {
                $q->tagged($tags);
            }
        }

        match ($request->input('sort')) {
            'oldest' => $q->oldest('application_date'),
            'company' => $q->orderBy('company'),
            default => $q->latest('application_date'),
        };

        return $q;
    }
}
