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

    // ── Placeholder routes (built Day 6 onwards) ──────────────────────────
    // Farmers - full CRUD
    Route::resource('farmers', \App\Http\Controllers\FarmerController::class);

    // Lands & Crops
    Route::get('/lands',  fn() => 'Lands - coming Day 7')->name('lands.index');
    Route::get('/crops',  fn() => 'Crops - coming Day 7')->name('crops.index');

    // SHG
    Route::get('/shgs',  fn() => 'SHGs - coming Day 8')->name('shgs.index');

    // Schemes & Applications
    Route::get('/schemes',                          fn() => 'Schemes - coming Day 9')->name('schemes.index');
    Route::get('/applications',                     fn() => 'Applications - coming Day 9')->name('applications.index');
    Route::get('/applications/create',              fn() => 'Apply - coming Day 9')->name('applications.create');
    Route::get('/applications/{id}',                fn($id) => 'Application show - coming Day 9')->name('applications.show');

    // Reports
    Route::get('/reports', fn() => 'Reports - coming Day 11')->name('reports.index');
});

require __DIR__.'/auth.php';
