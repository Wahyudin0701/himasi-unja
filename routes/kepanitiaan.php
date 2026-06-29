<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Kepanitiaan\EventController;
use App\Http\Controllers\Kepanitiaan\EventDivisionController;
use App\Http\Controllers\Kepanitiaan\CommitteeController;
use App\Http\Controllers\Kepanitiaan\KetupelDashboardController;
use App\Http\Controllers\Kepanitiaan\CODashboardController;
use App\Http\Controllers\Kepanitiaan\AnggotaDashboardController;

// Sprint 1: Event Core & Struktur Kepanitiaan
Route::prefix('kepanitiaan')->middleware('auth')->group(function () {
    // Event CRUD
    Route::resource('events', EventController::class);
    
    // Nested resources for event structure
    Route::prefix('events/{event}')->group(function () {
        Route::resource('divisions', EventDivisionController::class)->names('events.divisions');
        Route::resource('committees', CommitteeController::class)->names('events.committees');
        Route::post('committees/volunteer', [CommitteeController::class, 'storeVolunteer'])->name('events.committees.volunteer');
    });

    // ===== DASHBOARD KEPANITIAAN (Role-Based) =====



    // ----------------------------------------------------
    // KETUPEL (Ketua Pelaksana & Wakil Ketupel)
    // ----------------------------------------------------
    Route::prefix('ketupel')->name('kepanitiaan.ketupel.')->group(function () {
        Route::get('/dashboard', [KetupelDashboardController::class, 'index'])->name('dashboard');
        Route::get('/events/{event}/manage-team', [KetupelDashboardController::class, 'manageTeam'])->name('manage-team');
        
        // Manajemen Tim
        Route::post('/events/{event}/team', [KetupelDashboardController::class, 'storeTeamMember'])->name('store-team');
        Route::put('/events/{event}/team/{committee}', [KetupelDashboardController::class, 'updateTeamMember'])->name('update-team');
        Route::delete('/events/{event}/team/{committee}', [KetupelDashboardController::class, 'removeTeamMember'])->name('remove-team');

        // Manajemen Divisi (oleh Ketupel)
        Route::get('/events/{event}/divisions/create', [KetupelDashboardController::class, 'createDivision'])->name('create-division');
        Route::post('/events/{event}/divisions', [KetupelDashboardController::class, 'storeDivision'])->name('store-division');
        Route::delete('/events/{event}/divisions/{division}', [KetupelDashboardController::class, 'destroyDivision'])->name('destroy-division');

        // Progres Divisi untuk Ketupel
        Route::get('/progres-divisi', [App\Http\Controllers\Kepanitiaan\MemberController::class, 'ketupelIndex'])->name('progres.divisi');

        // Manajemen Panitia (auto-redirect ke manage-team event aktif)
        Route::get('/manajemen-panitia', [KetupelDashboardController::class, 'manajemenPanitia'])->name('manajemen-panitia');
    });

    // CO Dashboard
    Route::prefix('co')->name('kepanitiaan.co.')->group(function () {
        Route::get('/dashboard', [CODashboardController::class, 'index'])->name('dashboard');
        
        // Progres Divisi
        Route::get('/progres-divisi', [App\Http\Controllers\Kepanitiaan\MemberController::class, 'index'])->name('progres.divisi');
        
        // Pengaturan Sprint
        Route::get('/sprints', [CODashboardController::class, 'manageSprints'])->name('sprints.index');
        Route::post('/sprints', [CODashboardController::class, 'storeSprint'])->name('sprints.store');
        Route::put('/sprints/{sprint}', [CODashboardController::class, 'updateSprint'])->name('sprints.update');
        Route::delete('/sprints/{sprint}', [CODashboardController::class, 'destroySprint'])->name('sprints.destroy');
        
        // Tugas (Sprint)
        Route::get('/tasks/create', [CODashboardController::class, 'createTask'])->name('tasks.create');
        Route::get('/tasks/{task}', [CODashboardController::class, 'showTask'])->name('tasks.show');
        Route::get('/tasks/{task}/edit', [CODashboardController::class, 'editTask'])->name('tasks.edit');
        Route::post('/tasks', [CODashboardController::class, 'storeTask'])->name('tasks.store');
        Route::patch('/tasks/{task}', [CODashboardController::class, 'updateTask'])->name('tasks.update');
        Route::post('/tasks/{task}/review', [CODashboardController::class, 'reviewTask'])->name('tasks.review');
        Route::delete('/tasks/{task}', [CODashboardController::class, 'destroyTask'])->name('tasks.destroy');
    });

    // Anggota Dashboard
    Route::prefix('anggota')->group(function () {
        Route::get('/dashboard', [AnggotaDashboardController::class, 'index'])->name('kepanitiaan.anggota.dashboard');
        Route::get('/tasks/{task}', [AnggotaDashboardController::class, 'show'])->name('kepanitiaan.anggota.tasks.show');
        Route::patch('/tasks/{task}/status', [AnggotaDashboardController::class, 'updateTaskStatus'])->name('kepanitiaan.anggota.update-status');
    });
});
