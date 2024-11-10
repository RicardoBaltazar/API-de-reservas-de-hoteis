<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware('role:manager')->group(function () {
    // Only managers can access these routes
});

// Or for multiple roles
Route::middleware('role:manager|admin')->group(function () {
    // Managers or admins can access these routes
});

require __DIR__ . '/auth.php';
