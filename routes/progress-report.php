<?php

use Illuminate\Support\Facades\Route;

// Akan diisi di Sprint 3
Route::prefix('tasks')->middleware(['auth'])->group(function () {
    // Route::resource('/', WorkTaskController::class);
    // ...
});
