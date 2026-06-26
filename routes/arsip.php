<?php

use Illuminate\Support\Facades\Route;

// Akan diisi di Sprint 7
Route::prefix('arsip')->middleware(['auth'])->group(function () {
    // Route::get('/', [ArchiveController::class, 'index']);
    // ...
});
