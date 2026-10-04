<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/guide', function () {
        return view('guide');
    })->name('guide');

    // Applications
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/export.csv', [ApplicationController::class, 'export'])->name('applications.export');
    Route::get('/applications/board', [ApplicationController::class, 'board'])->name('applications.board');
    Route::get('/applications/create', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::get('/applications/{application}/edit', [ApplicationController::class, 'edit'])->name('applications.edit');
    Route::put('/applications/{application}', [ApplicationController::class, 'update'])->name('applications.update');
    Route::delete('/applications/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');
    Route::post('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.status.update');
    Route::post('/applications/{application}/follow-up', [ApplicationController::class, 'updateFollowUp'])->name('applications.follow-up.update');
    Route::get('/applications/{application}/draft-follow-up', [ApplicationController::class, 'draftFollowUp'])->name('applications.follow-up.draft');
    Route::post('/applications/bulk', [ApplicationController::class, 'bulk'])->name('applications.bulk');

    // Interviews (nested under application)
    Route::post('/applications/{application}/interviews', [InterviewController::class, 'store'])->name('applications.interviews.store');
    Route::put('/applications/{application}/interviews/{interview}', [InterviewController::class, 'update'])->name('applications.interviews.update');
    Route::delete('/applications/{application}/interviews/{interview}', [InterviewController::class, 'destroy'])->name('applications.interviews.destroy');

    // Interviews (overview)
    Route::get('/interviews', [InterviewController::class, 'index'])->name('interviews.index');
    Route::get('/interviews/calendar', [InterviewController::class, 'calendar'])->name('interviews.calendar');

    // Documents
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
