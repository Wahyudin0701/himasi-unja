<?php

use Illuminate\Support\Facades\Route;

// Akan diisi di Sprint 4 & 5
Route::prefix('kepengurusan')->middleware(['auth'])->group(function () {
    // Route::resource('periods', PeriodController::class);
    // Route::resource('divisions', DivisionController::class);
    // ...
});
