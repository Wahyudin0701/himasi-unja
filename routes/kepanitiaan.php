<?php

use Illuminate\Support\Facades\Route;

// Akan diisi di Sprint 1 & 2
Route::prefix('kepanitiaan')->middleware(['auth'])->group(function () {
    // Route::resource('events', EventController::class);
    // ...
});
