<?php

namespace App\Http\Controllers;

use App\Http\Requests\InterviewRequest;
use App\Models\Application;
use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InterviewController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $mine = Interview::with('application')
            ->whereHas('application', fn ($q) => $q->where('user_id', $user->id))
            ->get();

        $upcoming = $mine
            ->filter(fn ($i) => $i->schedule_date && $i->schedule_date->isFuture())
            ->sortBy('schedule_date')
            ->values();
        $past = $mine
            ->filter(fn ($i) => $i->schedule_date && $i->schedule_date->isPast())
            ->sortByDesc('schedule_date')
            ->values();
        $unscheduled = $mine
            ->filter(fn ($i) => $i->schedule_date === null)
            ->values();

        return view('interviews.index', [
            'upcoming' => $upcoming,
            'past' => $past,
            'unscheduled' => $unscheduled,
        ]);
    }

    public function store(InterviewRequest $request, Application $application)
    {
        abort_unless($application->user_id === $request->user()->id, 403);
        $interview = $application->interviews()->create($request->validated());
        $application->log('interview_added', null, $interview->interview_stage, null, $request->user()->id);
        return redirect()
            ->route('applications.show', $application)
            ->with('success', 'Jadwal wawancara berhasil dicatat.');
    }

    public function update(InterviewRequest $request, Application $application, Interview $interview)
    {
        abort_unless($application->user_id === $request->user()->id, 403);
        abort_unless($interview->application_id === $application->id, 403);
        $interview->update($request->validated());
        return redirect()
            ->route('applications.show', $application)
            ->with('success', 'Jadwal wawancara diperbarui.');
    }

    public function destroy(Request $request, Application $application, Interview $interview)
    {
        abort_unless($application->user_id === $request->user()->id, 403);
        abort_unless($interview->application_id === $application->id, 403);
        $interview->delete();
        return redirect()
            ->route('applications.show', $application)
            ->with('success', 'Jadwal wawancara dihapus.');
    }

    /**
     * Month calendar view of interviews.
     */
    public function calendar(Request $request): View
    {
        $user = $request->user();
        $year = $request->integer('year', now()->year);
        $month = $request->integer('month', now()->month);

        $first = now()->setDate($year, $month, 1)->startOfMonth()->copy();
        $last = $first->copy()->endOfMonth();

        $interviews = Interview::with('application')
            ->whereHas('application', fn ($q) => $q->where('user_id', $user->id))
            ->whereNotNull('schedule_date')
            ->whereBetween('schedule_date', [$first->toDateString(), $last->toDateString()])
            ->get()
            ->groupBy(fn ($i) => $i->schedule_date->toDateString());

        return view('interviews.calendar', [
            'interviews' => $interviews,
            'year' => $year,
            'month' => $month,
            'first' => $first,
            'last' => $last,
        ]);
    }
}
