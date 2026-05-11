<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ─── Public Landing ───────────────────────────────────────────────────────────
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// ─── Auth Redirect Hub ────────────────────────────────────────────────────────
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'admin')   return redirect()->route('admin.dashboard');
    if ($role === 'officer') return redirect()->route('officer.dashboard');
    return redirect()->route('farmer.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
});

// ─── Officer Routes ───────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin,officer'])->prefix('officer')->name('officer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'officer'])->name('dashboard');
});

// ─── Farmer Routes ────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:farmer'])->prefix('farmer')->name('farmer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'farmer'])->name('dashboard');
});

// ─── Shared Authenticated Routes ──────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications - Day 12
    Route::get('/notifications',                    [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read',     [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::get('/notifications/{id}/read',          [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.read');
    Route::delete('/notifications/{id}',            [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');

    // ── Placeholder routes (built Day 6 onwards) ──────────────────────────
    // Farmers - full CRUD
    Route::resource('farmers', \App\Http\Controllers\FarmerController::class);

    // Lands & Crops - Day 7
    Route::resource('lands', \App\Http\Controllers\LandController::class)->except(['show']);
    Route::resource('crops', \App\Http\Controllers\CropController::class)->only(['index','create','store','destroy']);
    Route::get('/crop-history',         [\App\Http\Controllers\CropHistoryController::class, 'index'])->name('crop-history.index');
    Route::get('/crop-history/create',  [\App\Http\Controllers\CropHistoryController::class, 'create'])->name('crop-history.create');
    Route::post('/crop-history',        [\App\Http\Controllers\CropHistoryController::class, 'store'])->name('crop-history.store');
    Route::delete('/crop-history/{cropHistory}', [\App\Http\Controllers\CropHistoryController::class, 'destroy'])->name('crop-history.destroy');

    // SHG / FPG - Day 8
    Route::resource('shgs', \App\Http\Controllers\ShgController::class);
    Route::post('/shgs/{shg}/members',         [\App\Http\Controllers\ShgController::class, 'addMember'])->name('shgs.members.add');
    Route::delete('/shgs/{shg}/members/{member}', [\App\Http\Controllers\ShgController::class, 'removeMember'])->name('shgs.members.remove');

    // Schemes - Day 9
    Route::resource('schemes', \App\Http\Controllers\SchemeController::class);
    Route::post('/schemes/{scheme}/toggle', [\App\Http\Controllers\SchemeController::class, 'toggleStatus'])->name('schemes.toggle');

    // Applications - Day 9
    Route::resource('applications', \App\Http\Controllers\SchemeApplicationController::class)->except(['edit','update']);
    Route::post('/applications/{application}/approve', [\App\Http\Controllers\SchemeApplicationController::class, 'approve'])->name('applications.approve');
    Route::post('/applications/{application}/reject',  [\App\Http\Controllers\SchemeApplicationController::class, 'reject'])->name('applications.reject');

    // Reports & Analytics - Day 10 (admin + officer only)
    Route::middleware('role:admin,officer')->group(function () {
        Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');

        // PDF Downloads - Day 11
        Route::get('/pdf/farmer/{farmer}',    [\App\Http\Controllers\PdfController::class, 'farmerProfile'])->name('pdf.farmer');
        Route::get('/pdf/scheme/{scheme}',    [\App\Http\Controllers\PdfController::class, 'schemeSummary'])->name('pdf.scheme');
        Route::get('/pdf/analytics',          [\App\Http\Controllers\PdfController::class, 'analyticsSummary'])->name('pdf.analytics');
    });
});

require __DIR__.'/auth.php';
