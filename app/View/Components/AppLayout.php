<?php

namespace App\View\Components;

use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $paletteApplications = Auth::check()
            ? Application::where('user_id', Auth::id())
                ->select('id', 'company', 'title', 'status')
                ->latest('created_at')
                ->limit(100)
                ->get()
            : collect();

        return view('layouts.app', ['paletteApplications' => $paletteApplications]);
    }
}
