<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Kepengurusan\KadivDashboardController;
use App\Http\Controllers\Kepengurusan\KadivProkerController;

// Akan diisi di Sprint 4 & 5
Route::prefix('kepengurusan')->middleware(['auth'])->group(function () {
    // Route::resource('periods', PeriodController::class);
    // Route::resource('divisions', DivisionController::class);
    // Buku Direktori (Global)
    Route::get('/directory', [\App\Http\Controllers\Kepengurusan\DirectoryController::class, 'index'])->name('kepengurusan.directory.index');

    // Sekretaris Routes
    Route::prefix('sekretaris')->middleware(['role:sekretaris,kahim,wakahim,bendahara'])->name('kepengurusan.sekretaris.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Kepengurusan\SekretarisDashboardController::class, 'index'])->name('dashboard');
        // Data Pengurus routes are moved to Super Admin.
        // Directory route will be added soon.
    });

    // Anggota Routes
    Route::prefix('anggota')->middleware(['role:anggota'])->name('kepengurusan.anggota.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Kepengurusan\AnggotaDashboardController::class, 'index'])->name('dashboard');
        
        // Kanban Proker Non-Event
        Route::get('/proker', [\App\Http\Controllers\Kepengurusan\Anggota\AnggotaProkerController::class, 'index'])->name('proker.kanban');
        Route::get('/proker/{task}', [\App\Http\Controllers\Kepengurusan\Anggota\AnggotaProkerController::class, 'show'])->name('proker.show');
        Route::patch('/proker/{task}/submit-review', [\App\Http\Controllers\Kepengurusan\Anggota\AnggotaProkerController::class, 'submitReview'])->name('proker.submit-review');
    });

    // Kadiv Routes
    Route::prefix('kadiv')->middleware(['role:kadiv'])->name('kepengurusan.kadiv.')->group(function () {
        Route::get('/dashboard', [KadivDashboardController::class, 'index'])->name('dashboard');
        Route::get('/progres-divisi', [KadivDashboardController::class, 'progresDivisi'])->name('progres-divisi');
        Route::get('/proker', [KadivProkerController::class, 'index'])->name('proker.index');
        Route::get('/proker/create', [KadivProkerController::class, 'create'])->name('proker.create');
        Route::post('/proker', [KadivProkerController::class, 'store'])->name('proker.store');
        Route::get('/proker/{proker}', [KadivProkerController::class, 'show'])->name('proker.show');
        Route::get('/proker/{proker}/progress', [KadivProkerController::class, 'progress'])->name('proker.progress');
        Route::get('/proker/{proker}/progress/divisi/{division}', [KadivProkerController::class, 'divisionProgress'])->name('proker.division-progress');
        Route::get('/proker/{proker}/edit', [KadivProkerController::class, 'edit'])->name('proker.edit');
        Route::put('/proker/{proker}', [KadivProkerController::class, 'update'])->name('proker.update');
        Route::patch('/proker/{proker}/cancel', [KadivProkerController::class, 'cancel'])->name('proker.cancel');
        
        // Task Management for Proker Non-Event
        Route::post('/proker/{proker}/tasks', [\App\Http\Controllers\Kepengurusan\Kadiv\KadivProkerTaskController::class, 'store'])->name('proker.tasks.store');
        Route::put('/proker/{proker}/tasks/{task}', [\App\Http\Controllers\Kepengurusan\Kadiv\KadivProkerTaskController::class, 'update'])->name('proker.tasks.update');
        Route::delete('/proker/{proker}/tasks/{task}', [\App\Http\Controllers\Kepengurusan\Kadiv\KadivProkerTaskController::class, 'destroy'])->name('proker.tasks.destroy');
        
        // Review Proker Non-Event
        Route::get('/review-proker', [\App\Http\Controllers\Kepengurusan\Kadiv\KadivProkerReviewController::class, 'index'])->name('proker.review.index');
        Route::patch('/review-proker/{task}/approve', [\App\Http\Controllers\Kepengurusan\Kadiv\KadivProkerReviewController::class, 'approve'])->name('proker.review.approve');
        Route::patch('/review-proker/{task}/revise', [\App\Http\Controllers\Kepengurusan\Kadiv\KadivProkerReviewController::class, 'revise'])->name('proker.review.revise');
    });

    // Messaging Routes (accessible by all authenticated users)
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Messaging\MessageController::class, 'index'])->name('index');
        Route::get('/download/{message}', [\App\Http\Controllers\Messaging\MessageController::class, 'download'])->name('download');
        Route::get('/{channel}', [\App\Http\Controllers\Messaging\MessageController::class, 'show'])->name('show');
        Route::post('/{channel}', [\App\Http\Controllers\Messaging\MessageController::class, 'store'])->name('store');
    });
});
