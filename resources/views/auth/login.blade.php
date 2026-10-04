<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Log in · {{ config('app.name', 'JobTracker Pro') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col lg:flex-row">

        {{-- ============ Landing side ============ --}}
        <aside class="relative hidden lg:flex lg:flex-1 xl:flex-[1.4] bg-gray-950 text-white flex-col justify-between overflow-hidden">
            {{-- backdrop glows + grid --}}
            <div class="absolute inset-0" aria-hidden="true">
                <div class="absolute -top-40 -left-40 h-[560px] w-[560px] rounded-full bg-indigo-600/30 blur-3xl"></div>
                <div class="absolute bottom-0 right-0 h-[420px] w-[420px] rounded-full bg-sky-500/20 blur-3xl"></div>
                <div class="absolute inset-0 opacity-[0.15]"
                     style="background-image: linear-gradient(to right, rgba(255,255,255,.08) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,.08) 1px, transparent 1px); background-size: 56px 56px;"></div>
            </div>

            <div class="relative z-10 flex-1 flex flex-col justify-center px-12 xl:px-20 py-14">
                <div class="flex items-center gap-2.5 mb-10">
                    <span class="h-8 w-8 rounded-lg bg-white/10 ring-1 ring-white/20 flex items-center justify-center text-xs font-bold tracking-wider">JT</span>
                    <span class="font-semibold text-sm tracking-tight text-gray-100">JobTracker <span class="text-[11px] font-medium text-gray-500 uppercase tracking-widest">Pro</span></span>
                </div>

                <h1 class="text-4xl xl:text-5xl font-bold leading-tight tracking-tight text-white">
                    Stop chasing applications.<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-sky-300">Start tracking them.</span>
                </h1>
                <p class="mt-5 text-base xl:text-lg text-gray-400 leading-relaxed max-w-xl">
                    One pipeline for every application, interview, and document — with automatic
                    reminders, follow-up drafts, and a calendar that keeps your week organized.
                </p>

                <ul class="mt-10 space-y-4 max-w-xl">
                    <li class="flex items-start gap-3.5">
                        <span class="mt-0.5 h-6 w-6 shrink-0 rounded-md bg-indigo-500/20 ring-1 ring-indigo-400/30 flex items-center justify-center">
                            <svg class="h-3.5 w-3.5 text-indigo-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3.75H6.75v1.5H9V3.75zM18.75 3.75H16.5v1.5h2.25V3.75zM3.75 9h1.5v1.5H3.75V9zM3.75 15h1.5v1.5H3.75V15zm13.5-6h2.25V10.5H17.25V9zm0 6h2.25V16.5H17.25V15z"/></svg>
                        </span>
                        <div>
                            <div class="text-sm font-semibold text-gray-100">Kanban pipeline</div>
                            <div class="text-sm text-gray-500">Drag applications across 8 stages — from "Applied" to "Offer".</div>
                        </div>
                    </li>
                    <li class="flex items-start gap-3.5">
                        <span class="mt-0.5 h-6 w-6 shrink-0 rounded-md bg-indigo-500/20 ring-1 ring-indigo-400/30 flex items-center justify-center">
                            <svg class="h-3.5 w-3.5 text-indigo-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                        </span>
                        <div>
                            <div class="text-sm font-semibold text-gray-100">Interview calendar</div>
                            <div class="text-sm text-gray-500">Every interview in a monthly grid, linked to its application.</div>
                        </div>
                    </li>
                    <li class="flex items-start gap-3.5">
                        <span class="mt-0.5 h-6 w-6 shrink-0 rounded-md bg-indigo-500/20 ring-1 ring-indigo-400/30 flex items-center justify-center">
                            <svg class="h-3.5 w-3.5 text-indigo-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        </span>
                        <div>
                            <div class="text-sm font-semibold text-gray-100">Document vault</div>
                            <div class="text-sm text-gray-500">CVs, portfolios and cover letters, sorted and always at hand.</div>
                        </div>
                    </li>
                    <li class="flex items-start gap-3.5">
                        <span class="mt-0.5 h-6 w-6 shrink-0 rounded-md bg-indigo-500/20 ring-1 ring-indigo-400/30 flex items-center justify-center">
                            <svg class="h-3.5 w-3.5 text-indigo-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a21.7 21.7 0 00-6.907-1.721M4.5 6.75v.243a21.7 21.7 0 016.907-1.721m0 0a21.66 21.66 0 0112.195 0"/></svg>
                        </span>
                        <div>
                            <div class="text-sm font-semibold text-gray-100">Follow-up drafts</div>
                            <div class="text-sm text-gray-500">One-click polite follow-up emails, ready to send to the right recruiter.</div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="relative z-10 px-12 xl:px-20 py-8 border-t border-white/10">
                <p class="text-xs text-gray-500">"The difference between people who land interviews<br class="hidden sm:block"> is that they follow up. JobTracker makes it effortless."</p>
            </div>
        </aside>

        {{-- ============ Login side ============ --}}
        <main class="flex-1 flex flex-col">
            <div class="flex-1 flex items-center justify-center px-6 py-12 lg:px-16">
                <div class="w-full max-w-[400px]">

                    {{-- mobile / top brand --}}
                    <div class="flex items-center gap-2.5 mb-10 lg:justify-center">
                        <span class="h-8 w-8 rounded-lg bg-gray-900 text-white flex items-center justify-center text-xs font-bold tracking-wider">JT</span>
                        <span class="font-semibold text-sm tracking-tight text-gray-900">JobTracker <span class="text-[11px] font-medium text-gray-400 uppercase tracking-widest">Pro</span></span>
                    </div>

                    <div class="mb-8 lg:text-center">
                        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Welcome back</h2>
                        <p class="mt-1.5 text-sm text-gray-500">Sign in to pick up where you left off.</p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="email" class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Email</label>
                            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                                   class="w-full text-sm border border-gray-300 rounded-md px-3.5 py-2.5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 transition"
                                   placeholder="you@example.com">
                            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1.5">Password</label>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                   class="w-full text-sm border border-gray-300 rounded-md px-3.5 py-2.5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 transition"
                                   placeholder="••••••••">
                            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                        </div>

                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-gray-600 cursor-pointer select-none">
                                <input id="remember_me" type="checkbox" name="remember"
                                       class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                Remember me
                            </label>
                            @if (Route::has('password.request'))
                                <a class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors" href="{{ route('password.request') }}">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 text-sm font-semibold text-white bg-gray-900 hover:bg-gray-800 px-4 py-2.5 rounded-md transition-colors">
                            Log in
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </button>
                    </form>

                    <div class="mt-6 border border-dashed border-gray-300 bg-gray-50 rounded-md px-4 py-3 text-xs text-gray-500">
                        <span class="font-semibold text-gray-600">Try the demo:</span>
                        <span class="text-gray-500">demo@jobtracker.test</span> &middot; password <span class="text-gray-500">password</span>
                    </div>

                    <p class="mt-8 text-center text-sm text-gray-500 lg:hidden">
                        JobTracker Pro — keep your job hunt on track.
                    </p>
                </div>
            </div>

            <div class="hidden lg:flex items-center justify-between px-16 pb-6 text-xs text-gray-400">
                <span>&copy; {{ date('Y') }} JobTracker Pro</span>
                <span class="flex items-center gap-4">
                    <a href="{{ route('guide') }}" class="hover:text-gray-600 transition-colors">User guide</a>
                    <span>Privacy</span>
                    <span>Terms</span>
                </span>
            </div>
        </main>
    </div>
</body>
</html>
