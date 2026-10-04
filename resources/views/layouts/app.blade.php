<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'JobTracker Pro') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
<div class="min-h-screen bg-gray-50 text-gray-900">
    {{-- Mobile top bar --}}
    <div class="sticky top-0 z-40 flex items-center justify-between h-14 px-4 bg-white border-b border-gray-200 lg:hidden">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <span class="font-semibold text-sm">JobTracker</span>
            <span class="text-[11px] font-medium text-gray-500 bg-gray-100 border border-gray-200 rounded px-1.5 py-0.5">Pro</span>
        </a>
        <div class="flex items-center gap-1">
            @foreach ([
                ['dashboard', 'dashboard', 'Dashboard'],
                ['applications.index', 'applications.*', 'Applications'],
                ['interviews.index', 'interviews.*', 'Interviews'],
            ] as $item)
                <a href="{{ route($item[0]) }}"
                   class="px-3 py-1.5 rounded-md text-sm {{ request()->routeIs($item[1]) ? 'text-gray-900 font-medium' : 'text-gray-500' }}">
                    {{ $item[2] }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="flex">
        {{-- Slim sidebar (desktop) --}}
        <aside class="hidden lg:flex flex-col w-56 shrink-0 border-r border-gray-200 bg-white h-screen sticky top-0 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-5 h-14 border-b border-gray-200">
                <span class="h-6 w-6 rounded bg-gray-900 text-white flex items-center justify-center text-[11px] font-bold">JT</span>
                <span class="font-semibold text-sm tracking-tight">JobTracker</span>
                <span class="text-[10px] font-medium text-gray-400 uppercase tracking-wide">Pro</span>
            </a>

            <nav class="flex-1 px-3 py-4 space-y-0.5">
                <div class="px-2 pb-2 text-[11px] font-medium text-gray-400 uppercase tracking-wide">Menu</div>

                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-md text-sm {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                    Dashboard
                </a>

                <a href="{{ route('applications.index') }}"
                   class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-md text-sm {{ request()->routeIs('applications.*') && !request()->routeIs('interviews.*') ? 'bg-gray-100 text-gray-900 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.05a2.625 2.625 0 01-2.625 2.625H6.375a2.625 2.625 0 01-2.625-2.625v-4.05m16.5 0V9.375c0-.621-.504-1.125-1.125-1.125h-.375M4.125 14.15V9.375c0-.621.504-1.125 1.125-1.125h.375m0 0h12.75m0 0l.945-.945a2.625 2.625 0 001.836-4.463l-.375-.375a1.125 1.125 0 00-1.59 0l-.18.18M6.75 16.5v4.5m0-4.5h12.75M9.75 19.5v3.75m0 0h3.75M18.75 19.5v3.75m0 0h3.75M18.75 23.25h3.75"/></svg>
                    Applications
                </a>

                <a href="{{ route('applications.board') }}"
                   class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-md text-sm {{ request()->routeIs('applications.board') ? 'bg-gray-100 text-gray-900 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3.75H6.75v1.5H9V3.75zM18.75 3.75H16.5v1.5h2.25V3.75zM3.75 9h1.5v1.5H3.75V9zM3.75 15h1.5v1.5H3.75V15zm13.5-6h2.25V10.5H17.25V9zm0 6h2.25V16.5H17.25V15zM9 9h4.5v6H9V9z"/></svg>
                    Pipeline
                </a>

                <a href="{{ route('interviews.index') }}"
                   class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-md text-sm {{ request()->routeIs('interviews.index') ? 'bg-gray-100 text-gray-900 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 11h14a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2z"/></svg>
                    Interviews
                </a>

                <a href="{{ route('interviews.calendar') }}"
                   class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-md text-sm {{ request()->routeIs('interviews.calendar') ? 'bg-gray-100 text-gray-900 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    Calendar
                </a>

                <a href="{{ route('documents.index') }}"
                   class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-md text-sm {{ request()->routeIs('documents.*') ? 'bg-gray-100 text-gray-900 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    Documents
                </a>

                <a href="{{ route('guide') }}"
                   class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-md text-sm {{ request()->routeIs('guide') ? 'bg-gray-100 text-gray-900 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.34-.087A4.5 4.5 0 1118 14.5H9.5A2.5 2.5 0 017 12h5.5M9.5 9a3.5 3.5 0 103.5 3.5M4.5 21.75h15A2.25 2.25 0 0021.75 19.5V4.5A2.25 2.25 0 0019.5 2.25h-15A2.25 2.25 0 002.25 4.5v15A2.25 2.25 0 004.5 21.75z"/></svg>
                    Guide
                </a>
            </nav>

            <div class="px-3 pb-4">
                <div class="border-t border-gray-200 pt-4 space-y-0.5">
                    <div class="px-2 pb-2 text-[11px] font-medium text-gray-400 uppercase tracking-wide">Settings</div>
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-md text-sm {{ request()->routeIs('profile.*') ? 'bg-gray-100 text-gray-900 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.904l.707.707a3.375 3.375 0 010 4.773l-.707.707m4.802 3.736l.707.707a3.375 3.375 0 010 4.773l-.707.707M3.94 8.754a3.375 3.375 0 00-.707-.707 3.375 3.375 0 000-4.773l.707-.707m11.754 9.846l.707-.707a3.375 3.375 0 000-4.773l-.707-.707"/></svg>
                        Profile &amp; Account
                    </a>
                </div>
            </div>

            <div class="px-5 py-4 border-t border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-semibold uppercase">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-medium truncate">{{ auth()->user()->name }}</div>
                        <form method="POST" action="{{ route('logout') }}" class="mt-0.5">
                            @csrf
                            <button type="submit" class="text-xs text-gray-400 hover:text-gray-600 transition">Sign out</button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main column --}}
        <div class="flex-1 min-w-0 flex flex-col">
            {{-- Top header: breadcrumbs + CTA --}}
            @php
                $crumbs = match(true) {
                    request()->routeIs('dashboard') => ['Dashboard'],
                    request()->routeIs('applications.index') => ['Applications'],
                    request()->routeIs('applications.create') => ['Applications', 'New'],
                    request()->routeIs('applications.edit') => ['Applications', 'Edit'],
                    request()->routeIs('applications.show') => ['Applications', request()->route('application')?->company ?? 'Detail'],
                    request()->routeIs('interviews.index') => ['Interviews'],
                    request()->routeIs('interviews.calendar') => ['Interviews', 'Calendar'],
                    request()->routeIs('documents.*') => ['Documents'],
                    request()->routeIs('guide') => ['Guide'],
                    request()->routeIs('profile.*') => ['Settings', 'Profile'],
                    default => ['Dashboard'],
                };
            @endphp
            <header class="hidden lg:flex items-center justify-between h-14 px-6 bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="hidden xl:flex items-center text-xs text-gray-400 select-none mr-4">
                    <kbd class="border border-gray-200 bg-gray-50 rounded px-1.5 py-0.5 text-gray-500 font-medium">⌘K</kbd>
                    <span class="ml-1">Quick actions</span>
                </div>
                <nav class="flex items-center gap-2 text-sm" aria-label="Breadcrumb">
                    @foreach ($crumbs as $i => $crumb)
                        @if ($i > 0)
                            <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        @endif
                        @if ($i === count($crumbs) - 1)
                            <span class="font-medium text-gray-900">{{ $crumb }}</span>
                        @elseif ($i === 0 && $crumb === 'Applications' && request()->routeIs('applications.show'))
                            <a href="{{ route('applications.index') }}" class="text-gray-500 hover:text-gray-900 transition">{{ $crumb }}</a>
                        @elseif ($i === 0)
                            <a href="{{ $crumb === 'Settings' ? route('profile.edit') : route('dashboard') }}" class="text-gray-500 hover:text-gray-900 transition">{{ $crumb }}</a>
                        @else
                            <span class="text-gray-500">{{ $crumb }}</span>
                        @endif
                    @endforeach
                </nav>

                @unless (request()->routeIs('applications.create') || request()->routeIs('applications.edit'))
                    <a href="{{ route('applications.create') }}"
                       class="inline-flex items-center gap-1.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium px-3.5 py-2 rounded-md transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        New Application
                    </a>
                @endunless
            </header>

            {{-- Page content --}}
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>

    <x-command-palette :applications="$paletteApplications" />
    <x-toast />
</div>

    </body>
</html>
