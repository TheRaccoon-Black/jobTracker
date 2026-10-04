<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Interview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $totalApplications = Application::where('user_id', $user->id)->count();

        $reachedInterview = Application::where('user_id', $user->id)
            ->whereIn('status', ['Assessment', 'HR Interview', 'User Interview', 'Offered'])
            ->count();
        $conversionRate = $totalApplications > 0 ? round(($reachedInterview / $totalApplications) * 100, 1) : 0;

        $statusDistribution = Application::where('user_id', $user->id)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recentApplications = Application::with(['interviews' => function ($q) {
            $q->orderByDesc('schedule_date')->limit(1);
        }])
            ->where('user_id', $user->id)
            ->latest('application_date')
            ->take(5)
            ->get();

        $activeCount = Application::where('user_id', $user->id)->active()->count();
        $offerCount = Application::where('user_id', $user->id)->where('status', 'Offered')->count();
        $rejectedCount = Application::where('user_id', $user->id)->whereIn('status', ['Rejected', 'Ghosted'])->count();

        // Pipeline funnel
        $funnelStages = [
            'Applied' => Application::where('user_id', $user->id)
                ->where('status', '!=', 'Wishlist')->count(),
            'Assessment' => Application::where('user_id', $user->id)
                ->whereIn('status', ['Assessment', 'HR Interview', 'User Interview', 'Offered'])->count(),
            'Interview' => Application::where('user_id', $user->id)
                ->whereIn('status', ['HR Interview', 'User Interview', 'Offered'])->count(),
            'Offer' => Application::where('user_id', $user->id)
                ->where('status', 'Offered')->count(),
        ];
        $funnelStages = collect($funnelStages)->map(fn ($count, $label) => [
            'label' => $label,
            'count' => $count,
            'pct' => $totalApplications > 0 ? round(($count / $totalApplications) * 100) : 0,
        ]);

        // Stale applications
        $staleApplications = Application::where('user_id', $user->id)->stale()->values()->sortByDesc('updated_at')->take(5)->values();

        // Upcoming interviews (next 14 days or soonest future)
        $upcomingInterviews = Interview::with('application')
            ->whereHas('application', fn ($q) => $q->where('user_id', $user->id))
            ->whereNotNull('schedule_date')
            ->where('schedule_date', '>=', now()->toDateString())
            ->orderBy('schedule_date')
            ->take(5)
            ->get();

        // Follow-ups due (today or overdue)
        $dueFollowUps = Application::where('user_id', $user->id)
            ->whereNotNull('next_follow_up')
            ->where('next_follow_up', '<=', now()->toDateString())
            ->orderBy('next_follow_up')
            ->get();

        // Weekly target progress (applications submitted this week)
        $weekStart = now()->startOfWeek();
        $thisWeek = Application::where('user_id', $user->id)
            ->where('created_at', '>=', $weekStart)
            ->count();
        $weeklyTarget = (int) ($user->weekly_target ?? 5);

        return view('dashboard', compact(
            'totalApplications',
            'conversionRate',
            'statusDistribution',
            'recentApplications',
            'activeCount',
            'offerCount',
            'rejectedCount',
            'funnelStages',
            'staleApplications',
            'upcomingInterviews',
            'dueFollowUps',
            'thisWeek',
            'weeklyTarget',
        ));
    }
}
